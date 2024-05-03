<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use App\Model\{Agent, Client, ClientPreference, Currency, Language, Country, SubscriptionPlansDriver, SubscriptionInvoicesDriver};
use Carbon\Carbon;

class SubscriptionPlansDriverController extends BaseController
{
    use ApiResponser;
    private $folderName = '/subscriptions/image';
    public function __construct()
    {
        $code = Client::orderBy('id','asc')->value('code');
        $this->folderName = '/'.$code.'/subscriptions/image';
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    /**
     * Get user subscriptions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request, $domain = '')
    {
        $sub_plans = SubscriptionPlansDriver::orderBy('id', 'asc')->get();
        // $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $user_subscriptions = SubscriptionInvoicesDriver::groupBy('driver_id')->get();
        // $showSubscriptionPlan = ShowSubscriptionPlanOnSignup::find(1);
        $subscribed_users_count = $user_subscriptions->count();
        $active_users = Agent::where('is_approved', 1)->count();
        $subscribed_users_percentage = ($subscribed_users_count / $active_users) * 100;
        $subscribed_users_percentage = number_format($subscribed_users_percentage, 2);
        // if($sub_plans){
        //     foreach($sub_plans as $plan){
        //         $features = '';
        //         if($plan->features->isNotEmpty()){
        //             $planFeaturesList = array();
        //             foreach($plan->features as $feature){
        //                 $title = $feature->feature->title;
        //                 if($feature->feature_id == 2){
        //                     $title = $feature->percent_value . $title;
        //                 }
        //                 $planFeaturesList[] = $title;
        //             }
        //             unset($plan->features);
        //             $features = implode(', ', $planFeaturesList);
        //         }
        //         $plan->features = $features;
        //     }
        // }
        return view('subscriptions/subscriptionPlansDriver')->with(['subscription_plans'=>$sub_plans, 'subscribed_users_count'=>$subscribed_users_count, 'subscribed_users_percentage'=>$subscribed_users_percentage]);
    }

    /**
     * save user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveSubscriptionPlan(Request $request, $domain = '', $slug='')
    {
        $message = 'added';
        $rules = array(
            'title' => 'required|string|max:50',
            // 'features' => 'required',
            'price' => 'required',
            'frequency' => 'required',
            'driver_type' => 'required',
            'commission_fixed' => 'required',
            'commission_percentage' => 'required',
            // 'period' => 'required',
            // 'sort_order' => 'required'
        );
        if(!empty($slug)){
            $plan = SubscriptionPlansDriver::where('slug', $slug)->firstOrFail();
            $rules['title'] = $rules['title'].',id,'.$plan->id;
            $message = 'updated';
        }

        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        if(!empty($slug)){
            // $subFeatures = SubscriptionPlanFeaturesUser::where('subscription_plan_id', $plan->id)->whereNotIn('feature_id', $request->features)->delete();
        }else{
            $plan = new SubscriptionPlansDriver;
            $plan->slug = uniqid();
        }
        $plan->title = $request->title;
        $plan->price = $request->price;
        // $plan->period = $request->period;
        $plan->frequency = $request->frequency;
        $plan->driver_type = $request->driver_type;
        $plan->driver_commission_fixed = $request->commission_fixed;
        $plan->driver_commission_percentage = $request->commission_percentage;
        // $plan->sort_order = $request->sort_order;
        $plan->status = ($request->has('status') && $request->status == '1') ? '1' : '0';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $plan->image = Storage::disk('s3')->put($this->folderName, $file,'public');
        }
        if( ($request->has('description')) && (!empty($request->description)) ){
            $plan->description = $request->description;
        }
        $plan->save();
        // $planId = $plan->id;
        // if( ($request->has('features')) && (!empty($request->features)) ){
            // $plan->subFeatures()->sync($request->features);
            // foreach($request->features as $key => $val){

                // if($val == 2){
                //     $plan->subFeatures()->updateExistingPivot(['feature_id' => $val], ['percent_value' => $request->percent_value]);
                // }

                // if(!empty($slug)){
                //     $subFeature = SubscriptionPlanFeaturesUser::where('subscription_plan_id', $planId)->where('feature_id', $val)->first();
                //     if($subFeature){
                //         continue;
                //     }else{
                //         $subFeature = new SubscriptionPlanFeaturesUser();
                //         $subFeature->subscription_plan_id = $planId;
                //         $subFeature->feature_id = $val;
                //         if($val == 2){
                //             $subFeature->percent_value = $request->percent_value;
                //         }
                //     }
                //     $subFeature->save();
                // }
            // }
        // }
        return redirect()->back()->with('success', 'Subscription has been '.$message.' successfully.');
    }

    /**
     * edit user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editSubscriptionPlan(Request $request, $domain = '', $slug='')
    {
        $plan = SubscriptionPlansDriver::where('slug', $slug)->firstOrFail();
        // $planFeatures = SubscriptionPlanFeaturesUser::select('feature_id', 'percent_value')->where('subscription_plan_id', $plan->id)->get();
        // $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        // $subPlanFeaturesIds = array();
        // foreach($planFeatures as $feature){
        //     $subPlanFeaturesIds[] = $feature->feature_id;
        // }
        $returnHTML = view('subscriptions.edit-subscriptionPlanDriver')->with(['plan' => $plan])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * update user subscription status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSubscriptionPlanStatus(Request $request, $domain = '', $slug='')
    {
        $subscription = SubscriptionPlansDriver::where('slug', $slug)->firstOrFail();
        $subscription->status = $request->status;
        $subscription->save();
        return response()->json(array('success' => true, 'message'=>'Subscription status has been updated.'));
    }

    /**
     * update user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSubscriptionPlan(Request $request, $domain = '', $slug='')
    {
        try {
            $subscription = SubscriptionPlansDriver::where('slug', $slug)->firstOrFail();
            $subscription->delete();
            return redirect()->back()->with('success', 'Subscription has been deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Subscription cannot be deleted.');
        }
    }
}
