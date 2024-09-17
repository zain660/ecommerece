<?php

namespace Modules\AdminReport\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AdminReport\Services\AdminReportService;
use Modules\MultiVendor\Repositories\MerchantRepository;
use Modules\MultiVendor\Services\MerchantService;
use Yajra\DataTables\Facades\DataTables;

class AdminReportController extends Controller
{
    protected $adminReportService;

    public function __construct(AdminReportService $adminReportService)
    {
        $this->adminReportService = $adminReportService;
        $this->middleware('maintenance_mode');
    }
    public function visitor_index()
    {
        return view('adminreport::visitor_report.index');
    }
    public function get_visitor_data()
    {
        $data = $this->adminReportService->getVisitor();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('ip', function ($data) {
                return getNumberTranslate($data->visitors);
            })
            ->addColumn('agent', function ($data) {
                return $data->agent;
            })
            ->addColumn('device', function ($data) {
                return $data->device;
            })
            ->addColumn('location', function ($data) {
                return $data->location;
            })
            ->addColumn('date', function ($data) {
                return dateConvert($data->date);
            })
            ->toJson();
    }
    public function inhouse_product_sale(Request $request)
    {
        $sale_type = $request->sale_type;
        return view('adminreport::inhouse_product_sale.index', compact('sale_type'));
    }
    public function product_stock(Request $request)
    {
        $type = $request->type;
        if(isModuleActive('MultiVendor')){
            $merchantService = new MerchantService(new MerchantRepository());
            $sellers = $merchantService->getAllSeller();
            if ($request->has('seller_id')) {
                $seller_id = $request->seller_id;
                return view('adminreport::product_stock.index', compact('type', 'sellers', 'seller_id'));
            }
            return view('adminreport::product_stock.index', compact('type', 'sellers'));
        }else{
            return view('adminreport::product_stock.index', compact('type'));
        }
    }
    public function product_stock_data(Request $request)
    {
        $type = $request->type;
        if ($type == "all") {
            $data = $this->adminReportService->products();
        } elseif ($type == "seller") {
            $seller_id = $request->seller_id;
            $data = $this->adminReportService->sellerProducts($seller_id);
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product_name', function ($data) {
                return textLimit($data->product_name, 50);
            })
            ->addColumn('product_stock', function($data){
                return view('adminreport::product_stock.components._stock_td', compact('data'));
            })
            ->addColumn('seller', function ($data) {
                return ($data->seller->role->type == 'seller')?$data->seller->first_name . " " . $data->seller->last_name:app('general_setting')->company_name;
            })
            ->addColumn('product_type', function ($data) {
                return ($data->is_physical == 1) ? __("product.physical_product") : __("product.digital_product");
            })
            ->addColumn('brand', function ($data) {
                return $data->brand->name;
            })
            ->toJson();
    }
    public function wishlist(Request $request)
    {
        $type = $request->type;
        return view('adminreport::wishlist.index', compact('type'));
    }
    public function wishlist_data(Request $request)
    {
        $type = $request->type;
        if ($type == "product") {
            $data = $this->adminReportService->wishlistByProduct();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('product', function ($data) {
                    return @$data->product->product_name;
                })
                ->addColumn('number_of_user', function ($data) {
                    return getNumberTranslate($data->total);
                })
                ->toJson();
        } else {
            $data = $this->adminReportService->wishlistByUser();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($data) {
                    return $data->user->first_name . " " . $data->user->last_name;
                })
                ->addColumn('number_of_wishlist_product', function ($data) {
                    return getNumberTranslate($data->total);
                })
                ->toJson();
        }
    }
    public function wallet_recharge_history()
    {
        return view('adminreport::wallet_recharge_history.index');
    }
    public function wallet_recharge_history_data()
    {
        $data = $this->adminReportService->walletHistories();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function ($data) {
                return $data->user->first_name . " " . $data->user->last_name;
            })
            ->addColumn('email', function ($data) {
                return $data->user->email;
            })
            ->addColumn('type', function ($data) {
                return getNumberTranslate($data->type);
            })
            ->addColumn('amount', function ($data) {
                return getNumberTranslate($data->amount);
            })
            ->addColumn('payment_method', function ($data) {
                return $data->payment_method;
            })
            ->addColumn('payment_details', function ($data) {
                return $data->payment_details;
            })
            ->addColumn('txn_id', function ($data) {
                return $data->txn_id;
            })
            ->addColumn('date', function ($data) {
                return dateConvert($data->created_at);
            })
            ->toJson();
    }
    public function order(Request $request)
    {
        $type = $request->type;
        $start_date = NULL;
        $end_date = NULL;
        if ($request->has('start_date')) {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));
        }
        return view('adminreport::order.index', compact('type', 'start_date', 'end_date'));
    }
    public function order_data(Request $request)
    {
        if (isset($_GET['table'])) {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date . '+1 day'));
            $table = $_GET['table'];
            if ($table == 'pending') {
                $order = $this->adminReportService->order()->where('is_confirmed', 0)->whereBetween('created_at', [$start_date, $end_date]);
            } elseif ($table == 'confirmed') {
                $order = $this->adminReportService->order()->where('is_confirmed', 1)->whereBetween('created_at', [$start_date, $end_date]);
            } elseif ($table == 'completed') {
                $order = $this->adminReportService->order()->where('is_completed', 1)->whereBetween('created_at', [$start_date, $end_date]);
            } elseif ($table == 'inhouse') {
                $order = $this->adminReportService->order()->where('order_type', 'inhouse_order')->whereBetween('created_at', [$start_date, $end_date]);
            } elseif ($table == 'all') {
                $order = $this->adminReportService->order()->whereBetween('created_at', [$start_date, $end_date]);
            } else {
                $order = [];
            }
            return DataTables::of($order)
                ->addIndexColumn()
                ->addColumn('date', function ($order) {
                    return dateConvert($order->created_at);
                })
                ->addColumn('email', function ($order) {
                    return ($order->customer_id) ? @$order->customer->email : @$order->guest_info->shipping_email;;
                })
                ->addColumn('total_qty', function ($order) {
                    return getNumberTranslate($order->packages->sum('number_of_product'));
                })
                ->addColumn('total_amount', function ($order) {
                    return single_price($order->grand_total);
                })
                ->addColumn('order_status', function ($order) {
                    return view('ordermanage::order_manage.components._order_status_td', compact('order'));
                })
                ->addColumn('is_paid', function ($order) {
                    return view('ordermanage::order_manage.components._is_paid_td', compact('order'));
                })
                ->rawColumns(['order_status', 'is_paid', 'action'])
                ->make(true);
        } else {
            return [];
        }
    }
    public function payment(Request $request)
    {
        $payment_methods = $this->adminReportService->paymentMethod();
        $payment_method_id = $request->payment_method_id;
        return view('adminreport::payment.index', compact('payment_methods', 'payment_method_id'));
    }
    public function payment_data(Request $request)
    {
        $payment_method_id = $request->payment_method_id;
        if ($payment_method_id == 0) {
            $data = $this->adminReportService->payment();
        } else {
            $data = $this->adminReportService->paymentByMethod($payment_method_id);
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function ($data) {
                if($data->user){
                    return $data->user->first_name . " " . $data->user->last_name;
                }else{
                    return __('report.not_registered_user');
                }
            })
            ->addColumn('payment_method', function ($data) {
                $method = '';
                 switch ($data->method->method) {
                    case 'Cash On Delivery':
                        $method = __("payment_gatways.cash_on_delivery");
                        break;
                        case 'Wallet':
                        $method = __("payment_gatways.wallet");
                        break;
                        case 'PayPal':
                        $method = __("payment_gatways.paypal");
                        break;
                        case 'Stripe':
                        $method = __("payment_gatways.stripe");
                        break;
                        case 'PayStack':
                        $method = __("payment_gatways.paystack");
                        break;
                        case 'RazorPay':
                        $method = __("payment_gatways.razorpay");
                        break;
                        case 'PayTM':
                        $method = __("payment_gatways.paytm");
                        break;
                        case 'Instamojo':
                        $method = __("payment_gatways.instamojo");
                        break;
                        case 'Midtrans':
                        $method = __("payment_gatways.midtrans");
                        break;
                        case 'PayUMoney':
                        $method = __("payment_gatways.payumoney");
                        break;
                        case 'JazzCash':
                        $method = __("payment_gatways.jazzcash");
                        break;
                        case 'Google Pay':
                        $method = __("payment_gatways.google_pay");
                        break;
                        case 'FlutterWave':
                        $method = __("payment_gatways.flutter_wave_payment");
                        break;
                        case 'Bank Payment':
                        $method = __("payment_gatways.bank_payment");
                        break;
                        case 'Bkash':
                        $method = __("payment_gatways.bkash");
                        break;
                        case 'SslCommerz':
                        $method = __("payment_gatways.ssl_commerz");
                        break;
                        case 'Mercado Pago':
                        $method = __("payment_gatways.mercado_pago");
                        break;
                };
                return $method;
            })
            ->addColumn('amount', function ($data) {
                return getNumberTranslate($data->amount);
            })
            ->addColumn('payment_details', function ($data) {
                return $data->payment_details;
            })
            ->addColumn('txn_id', function ($data) {
                $trx = "";
                if ($data->txn_id = "None") {
                    $trx = __("common.none");
                }elseif ($data->txn_id == "Added By Admin") {
                    $trx =__("wallet.added_by_admin");
                }else{
                    $trx =$data->txn_id;
                }
                return $trx;
            })
            ->addColumn('date', function ($data) {
                return dateConvert($data->created_at);
            })
            ->toJson();
    }

    public function top_seller()
    {
        return view('adminreport::top_seller.index');
    }

    public function top_seller_data()
    {
        $data = $this->adminReportService->topSeller();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                return $data->user->first_name . " " . $data->user->last_name;
            })
            ->addColumn('email', function ($data) {
                return $data->user->email;
            })
            ->addColumn('phone', function ($data) {
                return getNumberTranslate($data->user->phone);
            })
            ->addColumn('seller_id', function ($data) {
                return $data->seller_id;
            })
            ->addColumn('total_sale_qty', function ($data) {
                return getNumberTranslate($data->total_sale_qty);
            })
            ->addColumn('shop_name', function ($data) {
                return $data->seller_shop_display_name;
            })
            ->addColumn('joined_at', function ($data) {
                return dateConvert($data->created_at);
            })
            ->toJson();
    }
    public function top_customer()
    {
        return view('adminreport::top_customer.index');
    }
    public function top_customer_data()
    {

        $data = $this->adminReportService->topCustomer();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                if($data->user) {
                 return   $data->user->first_name ?? '' . " " . $data->user->last_name ?? '';
                }else{
                    return "";
                }
            })
            ->addColumn('email', function ($data) {
                return $data->user->email ?? '';
            })
            ->addColumn('phone', function ($data) {
                return getNumberTranslate($data->user->phone ?? '');
            })
            ->addColumn('total', function ($data) {
                return getNumberTranslate(round($data->total, 2));
            })
            ->addColumn('joined_at', function ($data) {
                if($data->user) {
                     return dateConvert($data->created_at);
                }else{
                    return '';
                }
            })
            ->toJson();
    }
    public function top_selling_item()
    {
        return view('adminreport::top_selling_item.index');
    }
    public function top_selling_item_data()
    {
        $data = $this->adminReportService->topSellingItem();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('seller', function ($data) {
                return $data->seller->first_name . " " . $data->seller->last_name;
            })
            ->addColumn('product', function ($data) {
                return $data->product_name;
            })
            ->addColumn('total_sale', function ($data) {
                return getNumberTranslate( $data->total_sale);
            })
            ->addColumn('avg_rating', function ($data) {
                return getNumberTranslate($data->avg_rating);
            })
            ->toJson();
    }
    public function product_review()
    {
        return view('adminreport::product_review.index');
    }
    public function product_review_data()
    {
        $data = $this->adminReportService->productReview();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product', function ($data) {
                return $data->product->product_name;
            })
            ->addColumn('number_of_review', function ($data) {
                return getNumberTranslate($data->number_of_review);
            })
            ->addColumn('rating', function ($data) {
                return getNumberTranslate(round($data->rating, 2));
            })
            ->toJson();
    }
    public function seller_review()
    {
        return view('adminreport::seller_review.index');
    }
    public function seller_review_data()
    {
        $data = $this->adminReportService->sellerReview();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('seller', function ($data) {
                return $data->seller->first_name . " " . $data->seller->last_name;
            })
            ->addColumn('number_of_review', function ($data) {
                return getNumberTranslate($data->number_of_review);
            })
            ->addColumn('rating', function ($data) {
                return getNumberTranslate(round($data->rating, 2));
            })
            ->toJson();
    }
}
