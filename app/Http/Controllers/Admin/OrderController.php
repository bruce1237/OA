<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\contractMaker\MakeContract;
use App\Model\Cart;
use App\Model\Company;
use App\Model\Department;
use App\Model\Order;
use App\Model\OrderStatus;
use App\Model\PaymentMethod;
use App\Model\Service;
use App\Model\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    //
    public function generateOrder(Request $request)
    {
        $data = $request->post('data');
        //        dd($data);
        $data = json_decode($data, true);
        $orderId = 0;
        $orderTotal = $orderProfit = 0;
        //get cart details  
        $cartData = array();
        foreach ($data['services'] as $service) {
            $serviceObj = Service::find($service['serviceId']);
            $cartData[] = [
                'order_id' => &$orderId, //先设置, 当 orderId变化的时候, 自动变化
                'service_category' => $serviceObj->service_name,
                'service_id' => $service['serviceId'],
                'service_name' => $service['serviceName'],
                'service_price' => $service['servicePrice'],
                'service_cost' => $service['serviceCost'],
                'service_attributes' => $service['serviceAttributes'],
                'service_profit' => $service['servicePrice'] - $serviceObj->service_cost,
            ];
            $orderTotal += $service['servicePrice'];
            $orderProfit += ($service['servicePrice'] - $serviceObj->service_cost);
        }


        $companyData = Company::find($data['company_id']); //get company Data

        $paymentMethods = PaymentMethod::find($data['order_payment']); //get payemnt data
        $orderData = [
            'order_firm_id' => $data['firm_id'],
            'order_total' => $orderTotal,
            'order_profit' => $orderProfit,
            'order_client_id' => $data['order_client_id'],
            'order_company_name' => $companyData->company_name,
            'order_company_address' => $companyData->company_address,
            'order_company_tax_ref' => $companyData->company_tax_id,
            'order_company_account' => $companyData->company_account_number,
            'order_company_account_address' => $companyData->company_account_address,
            'order_contact_name' => $data['order_contact_name'],
            'order_contact_number' => $data['order_contact_number'],
            'order_contact_address' => $data['order_contact_address'],
            'order_contact_post_code' => $data['order_contact_post_code'],
            'order_post_addressee' => $data['order_post_addressee'],
            'order_post_contact' => $data['order_post_contact'],
            'order_post_address' => $data['order_post_address'],
            'order_post_code' => $data['order_post_code'],
            'order_payment_method_name' => $paymentMethods->payment_method_name,
            'order_payment_method_details' => json_encode($paymentMethods->payment_method_attributes),
            'order_taxable' => $data['order_taxable'],
            'order_tax_type' => $data['order_tax_type'],
            'order_memo' => $data['order_memo'],
            'order_staff_id' => Auth::guard('admin')->user()->staff_id,
        ];


        try {
            $orderId = Order::create($orderData)->order_id;
            foreach ($cartData as $cart) {
                Cart::create($cart);
            }
            $this->uploadFiles($request->file(), $orderId);

            $this->returnData['status'] = true;
            $this->returnData['msg'] = "添加成功,等待经理审批";
            $this->returnData['code'] = 1;
        } catch (\Exception $e) {
            $this->returnData['msg'] = "添加失败" . $e->getMessage();
        }
        return $this->returnData;
    }

    private function uploadFiles(array $files, $orderId)
    {
        foreach ($files as $file) {
            $file->storeAs("/order/REF/{$orderId}/", str_replace(" ", '', $file->getClientOriginalName()), 'CRM');
        }
    }

    public function updateCart(Request $request)
    {
        try {
            Cart::find($request->post('cart_id'))->update($request->post());
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "更新成功";
            $this->returnData['code'] = 1;
        } catch (\Exception $e) {
            $this->returnData['msg'] = "更新失败:" . $e->getMessage();
        }

        return $this->returnData;
    }

    public function updateOrder(Request $request)
    {

        $structuredData = $this->trimData($request->post()); //权限判定

        $orderStatusStage = OrderStatus::find($structuredData['order_status_code'])->getOriginal('order_status_category');



        try {

            if (in_array($orderStatusStage, [1, 2])) {

                $structuredData['order_stage'] = $orderStatusStage + 1;
            } else {

                $structuredData['order_stage'] = $orderStatusStage;
            }


            $this->uploadOrderSupportFiles($request);

            if ($structuredData['order_status_code'] == env('ORDER_VALID_STAGE')) {
                $this->generateContract($structuredData['order_id']);
            }


            Order::find($structuredData['order_id'])->update($structuredData);
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "更新成功";
            $this->returnData['code'] = 1;
        } catch (\Exception $e) {
            $this->returnData['msg'] = $e->getMessage() . $e->getFile() . $e->getLine();
        }

        return $this->returnData;
    }

    public function generateContract($orderId)
    {
        $makeContract = new MakeContract();
        $makeContract->makeContract($orderId);
    }

    public function trimData(array $data)
    {

        if ($this->getStaffDepartId() != env('FINANCE_DEPART')) {
            unset($data['order_settlement']);
            unset($data['order_settled_date']);
            unset($data['order_tax_ref']);
            unset($data['tax_number']);
            unset($data['tax_received_date']);
        }
        return $data;
    }

    public function uploadOrderSupportFiles(Request $request)
    {
        try {
            $this->uploadFiles($request->file(), $request->post('order_id'));
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "上传成功";
            $this->returnData['code'] = 1;
        } catch (\Exception $e) {
            $this->returnData['msg'] = "上传失败" . $e->getMessage();
        }
        return $this->returnData;
    }

    public function delOrder(Request $request)
    {
        if (Order::where('order_id', '=', $request->post('order_id'))
            ->where('order_status_code', '=', '1')->exists()
        ) {
            try {
                Order::destroy($request->post('order_id'));
                Cart::where('order_id', '=', $request->post('order_id'))->delete();
                $this->removeAllFiles("Order/REF/{$request->post('order_id')}", 'CRM');
                $this->returnData['status'] = true;
                $this->returnData['msg'] = "订单处理成功!";
                $this->returnData['code'] = 1;
            } catch (\Exception $e) {
                $this->returnData['msg'] = $e->getMessage();
            }
        } else {
            $this->returnData['msg'] = "订单已经通过审批,不可以无效!";
        }
        return $this->returnData;
    }

    public function removeAllFiles($folderPath, $disk)
    {
        return Storage::disk($disk)->deleteDirectory($folderPath);
    }

    public function orderList()
    {
        $staff_level = $this->getStaffLevel();

        $staffs = $this->getAssignableStaffs();
        $serviceStages = $this->getCartStage();
        $orderStage = $this->getOrderStage();
        $data = [

            'staffs' => $staffs,
            'service_stage' => $serviceStages,
            'order_stage' => $orderStage,
        ];
        return view('admin/Order/OrderList', ['data' => $data]);
    }

    public function getStaffLevel()
    {
        return Staff::find(Auth::guard('admin')->user()->staff_id)->staff_level;
    }

    public function getAssignableStaffs()
    {
        $departObj = Department::where('assignable', '=', '1')->get();
        $departs = array();
        foreach ($departObj as $depart) {
            array_push($departs, $depart->id);
        }

        $staffs = Staff::whereIn('department_id', $departs)->OrderBy('department_id')->OrderBy('staff_no')->get();
        return $staffs;
    }

    public function getCartStage()
    {
        return $stages = Cart::select('service_stage')->GroupBy('service_stage')->get();
    }

    public function getOrderStage()
    {
        return OrderStatus::all();
    }

    public function orderSearch(Request $request)
    {
        $postData = $request->post();

        foreach ($postData as $k => $v) {
            if (!$v || $v == null) {
                unset($postData[$k]);
            }
        }

        if (!$postData) {
            $this->returnData['msg'] = "请选择你要查找的信息!";
        } else {
            $orderList = $this->getOrderList($this->getStaffLevel(), $this->getStaffId(), $postData);
            $this->returnData['data'] = $orderList;
            $this->returnData['status'] = true;
            $this->returnData['code'] = 1;
        }


        return $this->returnData;
    }

    public function getOrderList($staff_level, $staff_id, array $condition)
    {
        if (!$staff_level) {
            $condition['staff_id'] = $staff_id;
        }
        $carts = Cart::leftJoin('orders', 'orders.order_id', '=', 'carts.order_id')
            ->leftJoin('clients', 'clients.client_id', '=', 'orders.order_client_id')
            ->leftJoin('order_status', 'order_status.order_status_id', '=', 'orders.order_status_code')
            ->leftJoin('staff', 'staff.staff_id', '=', 'clients.client_assign_to')
            ->where(function ($query) use ($condition) {
                if (key_exists('staff_id', $condition)) {
                    $query->where('clients.client_assign_to', '=', $condition['staff_id']);
                }
                if (key_exists('order_status_code', $condition)) {
                    $query->where('orders.order_status_code', '=', $condition['order_status_code']);
                }
                if (key_exists('client_name', $condition)) {
                    $sanitizedClient = filter_var($condition['client_name'], FILTER_SANITIZE_NUMBER_INT);
                    if (!$sanitizedClient) {
                        $query->where('clients.client_name', 'like', "%" . $condition['client_name'] . "%");
                    } elseif (strlen($sanitizedClient) == 11) {
                        $query->where('clients.client_mobile', '=', $condition['client_name']);
                    } elseif (strlen($sanitizedClient) > 0) {
                        $query->where('clients.client_id', '=', $condition['client_name']);
                    }
                }
                if (key_exists('service_name', $condition)) {
                    $query->where('carts.service_name', 'like', "%" . $condition['service_name'] . "%");
                }
                if (key_exists('created_at_starts', $condition)) {
                    $query->where('orders.created_at', '>=', $condition['created_at_starts']);
                }
                if (key_exists('created_at_ends', $condition)) {
                    $query->where('orders.created_at', '<=', $condition['created_at_ends']);
                }
            })
            ->where('orders.deleted_at', '=', null)
            ->where('clients.deleted_at', '=', null)
            ->where('carts.deleted_at', '=', null)
            ->select(
                'clients.client_id',
                'clients.client_name',
                'clients.client_mobile',
                'orders.order_id',
                'orders.created_at',
                'order_status.order_status_name',
                'staff.staff_name'
            )->get();

        $data = array();
        foreach ($carts as $order) {
            $data[$order->order_id]['order_id'] = $order->order_id;
            $data[$order->order_id]['client_name'] = $order->client_name;
            $data[$order->order_id]['client_mobile'] = $order->client_mobile;
            $data[$order->order_id]['created_at'] = date('Y-m-d', strtotime($order->created_at));
            $data[$order->order_id]['order_status_name'] = $order->order_status_name;
            $data[$order->order_id]['staff_name'] = $order->staff_name;
        }
        return $data;
    }

    public function getStaffId()
    {
        return Auth::guard('admin')->user()->staff_id;
    }

    public function getOrderDetail(Request $request)
    {

        try {
            $order_id = $request->post('order_id');
            $order = Order::find($order_id);
            $order->order_company_tax_ref = $this->SensitiveDataConvert($order->order_company_tax_ref);
            $order->created_date = $this->convertTimeStampToDate($order->created_at);
            $order->order_status_name = $this->getOrderStatusName($order->order_status_code);
            $order->order_status_option = $this->getAvailableOrderStatus($order->order_stage);

            $carts = Cart::where('order_id', '=', $order_id)->get();
            $order->carts = $carts;
            $order->files = $this->getOrderFiles($order->order_id);
            $this->returnData['status'] = true;
            $this->returnData['data'] = $order;
        } catch (\Exception $e) {
            $this->returnData['msg'] = $e->getMessage();
        }
        return $this->returnData;
    }

    public function getAvailableOrderStatus($orderStage)
    {

        $staff = Staff::find(Auth::guard('admin')->user()->staff_id);
        $staffDepart = Department::find($staff->getOriginal('department_id'));


        switch ($orderStage) {
            case "0": //待审批阶段: 合法性审批
                $statusOptions = OrderStatus::where('order_status_category', '=', "1")->get();

                if ($staff->staff_level == env('DEPARTMENT_CHIEF_LEVEL') &&  $staffDepart->getOriginal('assignable')) {
                    //业务部经理
                    return $statusOptions;
                }
                break;
            case "1": //付款审批: 有效性审批
                $statusOptions = OrderStatus::where('order_status_category', '=', "2")->get();

                if ($staff->getOriginal('department_id') == env("FINANCE_DEPART")) {
                    //财务部员工

                    return $statusOptions;
                }
                break;
            case "2": //状态更新阶段: 状态修改
            case "3": //状态更新阶段: 状态修改

                if ($staff->getOriginal('department_id') == env("FINANCE_DEPART")) {
                    //财务部员工
                    $statusOptions = OrderStatus::where('order_status_category', '=', "2")->get();

                    return $statusOptions;
                }

                if ($staff->getOriginal('department_id') == env("PROCESS_DEPART")) {
                    $statusOptions = OrderStatus::where('order_status_category', '=', "3")->get();
                    //流程部员工
                    return $statusOptions;
                }
                break;
        }
        return false;
    }

    public function getStaffDepartId()
    {
        return Staff::find(Auth::guard('admin')->user()->staff_id)->department_id;
    }

    public function SensitiveDataConvert($data)
    {
        if ($this->getStaffLevel() <= 3) {
            $patten = "";
            $hideDigits = (int)(strlen($data) * 0.6);
            for ($i = 0; $i <= $hideDigits; $i++) {
                $patten .= "*";
            }
            $data = substr_replace($data, $patten, 3, strlen($patten)); // tzzzt
        }
        return $data;
    }

    public function convertTimeStampToDate(string $timeStamp)
    {
        return date('Y-m-d', strtotime($timeStamp));
    }

    public function getOrderStatusName(int $orderStatusCode)
    {
        return OrderStatus::find($orderStatusCode)->order_status_name;
    }

    public function getOrderFiles($orderId)
    {
        $path = "Order/REF/{$orderId}/";
        $result = array();
        $files = Storage::disk('CRM')->allFiles($path);
        foreach ($files as $file) {
            $result[$file] = str_replace($path, '', $file);
        }
        return $result;
    }

    public function rmOrderFiles(Request $request)
    {
        try {
            $this->removeFile($request->post('filename'), 'CRM');
            $this->returnData['status'] = true;
            $this->returnData['msg'] = "文件移除成功";
            $this->returnData['code'] = 1;
        } catch (\Exception $e) {
            $this->returnData['msg'] = "文件移除失败: " . $e->getMessage();
        }

        return $this->returnData;
    }

    public function removeFile($filePathFullName, $disk)
    {
        return Storage::disk($disk)->delete($filePathFullName);
    }
}
