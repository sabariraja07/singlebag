@extends('main.page')
<style>
    .faq_aligin {
        margin: .5rem 0;
        margin-left: 33px;
        padding: 4px;
    }
</style>
@section('content-header')
    <div id="main-hero" class="hero-body">
        <div class="container has-text-centered">
            <div class="columns is-vcentered">
                <div class="column is-6 is-offset-3 has-text-centered is-subheader-caption">
                    <h1 class="title is-2">Frequently Asked Questions</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="section is-medium">
        <div class="container">
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">1. How do I get started?</h3>
            <div class="faq_aligin">
                <a href="{{ url(env('APP_URL') . '/partner/register') }}" target="_blank">{{ __('Please sign up here') }}</a></div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">2. Does it cost me anything to become a
                Partner?</h3>
            <div class="faq_aligin">
                The program is free to join. There are no monthly charges and no minimum sales requirements.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">3. I cannot find my Partner confirmation
                email.</h3>
            <div class="faq_aligin">
                Please check your Promotions tab, or your spam folders, first. If you still cannot find it, contact us at
                <a href="mailto:support@singlebag.com">support@singlebag.com</a></div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">4. What is the preferred mode of payment?
            </h3>
            <div class="faq_aligin">
                RazorPay is the preferred mode of payment.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">5. When will I get paid?</h3>
            <div class="faq_aligin">
                If the unpaid commissions displayed in your Partner account equals or exceeds 10,000 only then you can
                request a settlement.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">6. Who can I refer?</h3>
            <div class="faq_aligin">
                You can refer any legal business that is not an existing Singlebag customer under any paid subscription
                plan.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">7. Is there any way for me to track my
                earning performance?</h3>
            <div class="faq_aligin">
                You can log in to your partner dashboard at any time, and your earning performance will be displayed over
                there.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">8. My customer is looking for a discount to
                purchase the product.</h3>
            <div class="faq_aligin">
                No, we do not provide any discounts to the customers.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">9. Is there a limit on the number of
                customers I can referor the amount I can earn from this program?</h3>
            <div class="faq_aligin">
                There is no limit to the number of customers that you can refer, nor on the amount, you can earn from this
                program. You are encouraged to bring in as many new customers as you can.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">10. Will I be receiving a recurring
                commission every year? What if the customer cancels their subscription?</h3>
            <div class="faq_aligin">
                Yes, you’ll be receiving a recurring commission every year as long as the customer stays active.You will
                earn commissions on each sale after the customer subscribes to a plan.The commission will be paid to you
                based on the plan chosen by the customer.</div>
            <div class="faq_aligin">If the customer cancels a subscription plan or terminates their subscription, the
                commission will only be
                paid once, and it won’t be recurring next year.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">11. How much commission can I earn?</h3>
            <div class="faq_aligin">
                For each qualified sale that you generate, you earn 25% of the revenue share from Singlebag, provided a
                recurring revenue if the customer stays active every year.
                There is no limit to the number of customers that you can refer, nor on the amount, you can earn from this
                program. You are encouraged to bring in as many new customers as you can.</div>
            <h3 style="font-weight: 600; margin: 0; text-transform: uppercase">12. Where do I fill in my bank details?</h3>
            <div class="faq_aligin">
                Log in to your partner dashboard</div>
                <div class="faq_aligin">
                Click on profile - profile settings</div>
                <div class="faq_aligin">Enter your bank details present at the bottom of the page</div>
                    <div class="faq_aligin">Click on save.</div>
        </div>
    </div>
@endsection
