<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CustomerInvestment;
use App\Models\DeliveryMan;
use App\Models\InvestmentPackage;
use App\Models\InvestmentWithdrawal;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class InvestmentController extends Controller
{
    public function dashboard(Request $request)
    {
        $params = [
            'zone_id' => $request['zone_id'] ?? 'all',
            'module_id' => Config::get('module.current_module_id'),
            'statistics_type' => $request['statistics_type'] ?? 'overall',
            'user_overview' => $request['user_overview'] ?? 'overall',
            'commission_overview' => $request['commission_overview'] ?? 'this_year',
            'business_overview' => $request['business_overview'] ?? 'overall',
        ];

        session()->put('dash_params', $params);

        $delivery_man = DeliveryMan::with('last_location')->when(is_numeric($params['zone_id']), function ($q) use ($params) {
            return $q->where('zone_id', $params['zone_id']);
        })
            ->Zonewise()
            ->limit(2)->get('image');

        $active_deliveryman = DeliveryMan::when(is_numeric($params['zone_id']), function ($q) use ($params) {
            return $q->where('zone_id', $params['zone_id']);
        })
            ->Zonewise()->where('active',1)->count();

        $inactive_deliveryman = DeliveryMan::when(is_numeric($params['zone_id']), function ($q) use ($params) {
            return $q->where('zone_id', $params['zone_id']);
        })
            ->Zonewise()->where('application_status','approved')->where('active',0)->count();

        $unavailable_deliveryman = DeliveryMan::when(is_numeric($params['zone_id']), function ($q) use ($params) {
            return $q->where('zone_id', $params['zone_id']);
        })
            ->Zonewise()->where('active',1)->Unavailable()->count();

        $available_deliveryman = DeliveryMan::when(is_numeric($params['zone_id']), function ($q) use ($params) {
            return $q->where('zone_id', $params['zone_id']);
        })
            ->Zonewise()->where('active',1)->Available()->count();

        $newly_joined_deliveryman = DeliveryMan::when(is_numeric($params['zone_id']), function ($q) use ($params) {
            return $q->where('zone_id', $params['zone_id']);
        })
            ->Zonewise()->whereDate('created_at', '>=', now()->subDays(30)->format('Y-m-d'))->count();

        $deliveryMen = DeliveryMan::when(is_numeric($params['zone_id']), function ($q) use ($params) {
            return $q->where('zone_id', $params['zone_id']);
        })->zonewise()->available()->active()->get();

        $deliveryMen = Helpers::deliverymen_list_formatting($deliveryMen);

        $module_type = 'investment';
        return view("admin-views.dashboard-{$module_type}", compact('active_deliveryman','deliveryMen','unavailable_deliveryman','available_deliveryman','inactive_deliveryman','newly_joined_deliveryman','delivery_man', 'params','module_type'));
    }

    public function flexible_packages()
    {
        $module_type = 'investment';
        $packages = InvestmentPackage::where('type', 'flexible')->paginate();
        return view('admin-views.investment.flexible.index', compact('module_type', 'packages'));
    }

    public function flexible_package_create()
    {
        $module_type = 'investment';
        return view('admin-views.investment.flexible.create', compact('module_type'));
    }

    public function flexible_package_store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'amount'                => 'required',
            'monthly_interest_rate' => 'required',
            'status'                => 'required',
        ]);

        $package                        = new InvestmentPackage;
        $package->name                  = $request->name;
        $package->type                  = 'flexible';
        $package->amount                = $request->amount;
        $package->monthly_interest_rate = $request->monthly_interest_rate;
        $package->status                = $request->status;
        $package->save();

        return redirect()->route('admin.investment.flexible')->with('success', 'Package created successfully!');
    }

    public function flexible_package_edit($id)
    {
        $module_type = 'investment';
        $package = InvestmentPackage::find($id);
        return view('admin-views.investment.flexible.edit', compact('module_type', 'package'));
    }

    public function flexible_package_update(Request $request, $id)
    {
        $request->validate([
            'name'                  => 'required',
            'amount'                => 'required',
            'monthly_interest_rate' => 'required',
            'status'                => 'required',
        ]);

        $package                        = InvestmentPackage::find($id);
        $package->name                  = $request->name;
        $package->type                  = 'flexible';
        $package->amount                = $request->amount;
        $package->monthly_interest_rate = $request->monthly_interest_rate;
        $package->status                = $request->status;
        $package->save();

        return redirect()->route('admin.investment.flexible')->with('success', 'Package updated successfully!');
    }

    public function flexible_package_delete($id)
    {
        $package = InvestmentPackage::find($id);
        $package->delete();
        return redirect()->route('admin.investment.flexible')->with('success', 'Package deleted successfully!');
    }

    public function locked_in_packages()
    {
        $module_type = 'investment';
        $packages = InvestmentPackage::where('type', 'locked-in')->paginate(1);
        return view('admin-views.investment.locked-in.index', compact('module_type', 'packages'));
    }

    public function locked_in_package_create()
    {
        $module_type = 'investment';
        return view('admin-views.investment.locked-in.create', compact('module_type'));
    }

    public function locked_in_package_store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'amount'                => 'required',
            'monthly_interest_rate' => 'required',
            'duration_in_months'    => 'required',
            'status'                => 'required',
        ]);

        $package                        = new InvestmentPackage;
        $package->name                  = $request->name;
        $package->type                  = 'locked-in';
        $package->amount                = $request->amount;
        $package->monthly_interest_rate = $request->monthly_interest_rate;
        $package->duration_in_months    = $request->duration_in_months;
        $package->status                = $request->status;
        $package->save();

        return redirect()->route('admin.investment.locked-in')->with('success', 'Package created successfully!');
    }

    public function locked_in_package_edit($id)
    {
        $module_type = 'investment';
        $package = InvestmentPackage::find($id);
        return view('admin-views.investment.locked-in.edit', compact('module_type', 'package'));
    }

    public function locked_in_package_update(Request $request, $id)
    {
        $request->validate([
            'name'                  => 'required',
            'amount'                => 'required',
            'monthly_interest_rate' => 'required',
            'duration_in_months'    => 'required',
            'status'                => 'required',
        ]);

        $package                        = InvestmentPackage::find($id);
        $package->name                  = $request->name;
        $package->type                  = 'locked-in';
        $package->amount                = $request->amount;
        $package->monthly_interest_rate = $request->monthly_interest_rate;
        $package->duration_in_months    = $request->duration_in_months;
        $package->status                = $request->status;
        $package->save();

        return redirect()->route('admin.investment.locked-in')->with('success', 'Package updated successfully!');
    }

    public function locked_in_package_delete($id)
    {
        $package = InvestmentPackage::find($id);
        $package->delete();
        return redirect()->route('admin.investment.locked-in')->with('success', 'Package deleted successfully!');
    }

    public function customer_investments()
    {
        $module_type = 'investment';
        $investments = CustomerInvestment::paginate();
        return view('admin-views.investment.customer.investments', compact('module_type', 'investments'));
    }

    public function investment_withdrawals()
    {
        $module_type = 'investment';
        $withdrawals = InvestmentWithdrawal::paginate();
        return view('admin-views.investment.withdrawals', compact('module_type', 'withdrawals'));
    }

    public function withdrawal_pay($id)
    {
        $withdrawal = InvestmentWithdrawal::find($id);
        $withdrawal->paid_at = now();
        $withdrawal->save();

        return redirect()->route('admin.investment.investment-withdrawals')->with('success', 'Withdrawal paid successfully!');
    }

    public function customers_wallet_balance()
    {
        $module_type = 'investment';
        $customer_data = User::has('customer_investments')->paginate();
        return view('admin-views.investment.customer.wallet-balance', compact('module_type', 'customer_data'));
    }
}
