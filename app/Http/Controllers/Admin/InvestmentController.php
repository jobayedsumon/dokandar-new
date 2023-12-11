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

        $flexible    = InvestmentPackage::where('type', 'flexible')->count();
        $locked_in   = InvestmentPackage::where('type', 'locked-in')->count();
        $investments = CustomerInvestment::count();
        $withdrawals = InvestmentWithdrawal::count();
        $customers   = User::whereHas('customer_investments')->count();
        $invested    = CustomerInvestment::where('redeemed_at', null)->withSum('package', 'amount')->get()->sum('package_sum_amount');
        $withdrawn   = InvestmentWithdrawal::where('paid_at', '!=', null)->sum('withdrawal_amount');
        $profit      = CustomerInvestment::get()->sum('profit_earned');

        $module_type = 'investment';
        return view("admin-views.dashboard-{$module_type}", compact('params','module_type', 'flexible', 'locked_in', 'investments', 'withdrawals', 'customers', 'invested', 'withdrawn', 'profit'));
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
        $customer_data = User::whereHas('customer_investments')->paginate();
        return view('admin-views.investment.customer.wallet-balance', compact('module_type', 'customer_data'));
    }
}
