<aside class="col-lg-4">
    <!-- Account menu toggler (hidden on screens larger 992px)-->
    <div class="d-block d-lg-none p-4"><a class="btn btn-outline-accent d-block" href="#account-menu" data-toggle="collapse"><i class="czi-menu mr-2"></i>Account menu</a></div>
    <!-- Actual menu-->
    <div class="cz-sidebar-static h-100 p-0">
        <div class="secondary-nav collapse border-right" id="account-menu">

            <div class="bg-secondary p-4">
                <h3 class="font-size-sm mb-0 text-muted">{{ __('Manage') }}</h3>
            </div>

            <ul class="list-unstyled mb-0">
                <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('business.index') }}">
                    <i class="czi-store opacity-60 mr-2 text-danger"></i>{{__('Businesses')}}</a></li>
                    <!-- (muted text) <span class="font-size-sm text-muted ml-auto">$1,375.00</span>-->

                <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('campaign.index') }}">
                    <i class="czi-loudspeaker opacity-60 mr-2 text-success"></i>{{__('Campaigns')}}</a></li>

                <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('product.index') }}">
                    <i class="czi-package opacity-60 mr-2 text-info"></i>{{__('Products')}}</a></li>
                <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('stripe.index') }}">
                    <i class="czi-wallet opacity-60 mr-2"></i>{{__('Stripe Connect')}}</a>
                </li>
                <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('stripe.seller-charges') }}">
                    <i class="czi-money-bag opacity-60 mr-2"></i>{{__('Orders')}}</a>
                </li>

                <li class="border-bottom mb-0">
                    <a class="nav-link-style d-flex align-items-center px-4 py-3" href="<?php echo e(route('stripe.donations_received')); ?>">
                        <i class="czi-gift opacity-60 mr-2"></i><?php echo e(__('Donations received')); ?></a>
                </li>
            </ul>

            <div class="bg-secondary p-4">
                <h3 class="font-size-sm mb-0 text-muted">{{ __('My Account') }}</h3>
            </div>

            <ul class="list-unstyled mb-0">
                <li class="mb-2 border-bottom">
                    <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{route('user.index')}}">
                        <i class="czi-user opacity-60 mr-2"></i>{{__('Info')}}</a>
                </li>
                <li class="border-bottom mb-0">
                    <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('stripe.add-credit-card') }}">
                        <i class="czi-card opacity-60 mr-2"></i>{{__('Credit Card')}}</a>
                </li>
                <li class="border-bottom mb-2">
                    <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('stripe.charges') }}">
                        <i class="czi-basket opacity-60 mr-2"></i>{{__('Purchases')}}</a>
                </li>
                <li class="border-bottom mb-0">
                    <a class="nav-link-style d-flex align-items-center px-4 py-3" href="<?php echo e(route('stripe.donations_made')); ?>">
                        <i class="czi-gift opacity-60 mr-2"></i><?php echo e(__('Donations made')); ?></a>
                </li>
            </ul>
        </div>
    </div>
</aside>
