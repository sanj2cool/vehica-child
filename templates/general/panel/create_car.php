<?php

/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

    $vehicaUser = new \Vehica\Model\User\User(_wp_get_current_user());
    $vehicaIsPayPalEnabled = vehicaApp('current_base_currency_support_paypal') && vehicaApp('settings_config')->isPayPalEnabled();
?>
<?php //echo do_shortcode('[elementor-template id="46514"]'); ?>
<style>
	#changing-text {
    font-weight: bold;
    color: #ff6600;
    transition: opacity 0.5s ease-in-out;
	
}
.vehica-package-new__label {
	display:none !important;
}
</style>
<div style = "background:#edf8fe; width:100%; padding:50px 0px;" > 
   <div class="vehica-container">
    <div class="vehica-car-form">
        <div class="vehica-car-form__inner">
            <div style="padding:30px 0px;display:flex;align-items:center;" class="row">
                <div>
                    <h2 style="font-size:50px;font-weight:bold;margin-bottom:0px;">List today your
                        <span
                            style="color:#fcb500;transition: opacity 0.5s ease-in-out;"
                            id="changing-text"
                            class="moving">Car
                        </span>
                    </h2>
                    <p style=" font-family: 'Roboto';
                        font-size: 24px;
                        width: 629px;
                    ">It's fast, easy, safe and you'll reach the right audience looking for a travel car!
                                        </p>
                                        <button
                                            style="
                        padding: 10px 32px;
                        font-size: 21px;
                        font-family: 'Roboto' !important;!i;!;!u;!um;!u;!;
                        line-height: 28px;
                        background: #fcb500;
                        border: none;
                        border-radius: 11px;
                        color: #222;
                        margin-top: 15px;
                        font-weight: 800;
                    ">List your car now !
                    </button>
                </div>

                <div>
                    <img src="http://travelcar.test/carss.png"/>
                </div>
            </div>
            <?php get_template_part('templates/general/panel/achivments'); ?>
            

        </div>

    </div>
</div>
</div>
<?php get_template_part('templates/general/panel/how_it_work'); ?>
<div class="vehica-car-form">
    <div class="vehica-car-form__inner">

        <?php if (!is_user_logged_in()) : ?>
            <h3 class="vehica-car-form__heading">
                <?php echo esc_attr(vehicaApp('submit_vehicle_string')); ?>
            </h3>
        <?php endif; ?>

        <vehica-car-form
                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_create_car')) ?>"
                redirect-url="<?php echo esc_url($vehicaCurrentWidget->getCreateCarRedirectUrl()); ?>"
                :is-logged="<?php echo esc_attr(is_user_logged_in() ? 'true' : 'false'); ?>"
                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_create_car')); ?>"
                vehica-checkout-nonce="<?php echo esc_attr(wp_create_nonce('vehica_buy_package')); ?>"
                error-msg="<?php echo esc_attr(vehicaApp('error_required_fields_string')); ?>"
                confirmation-text="<?php echo esc_attr(vehicaApp('ok_string')); ?>"
                success-text="<?php echo esc_attr(vehicaApp('added_string')); ?>"
                order-not-paid-text="<?php echo esc_attr(vehicaApp('order_not_paid_string')); ?>"
                choose-package-text="<?php echo esc_attr(vehicaApp('choose_package_string')); ?>"
                :show-thank-you-modal="<?php echo esc_attr($vehicaCurrentWidget->showThankYouModal() ? 'true' : 'false'); ?>"
                :require-description="<?php echo esc_attr(vehicaApp('settings_config')->isDescriptionRequired() ? 'true' : 'false'); ?>"
                checkout-url="<?php echo esc_url(admin_url('admin-ajax.php?action=vehica/woocommerce/checkout')); ?>"
                :woocommerce-mode="<?php echo esc_attr(vehicaApp('woocommerce_mode') ? 'true' : 'false'); ?>"
            <?php if ($vehicaCurrentWidget->showPolicy()) : ?>
                :require-terms="true"
            <?php endif; ?>
            <?php if (vehicaApp('auto_title_fields')->isEmpty()) : ?>
                :require-name="true"
            <?php else : ?>
                :require-name="false"
            <?php endif; ?>
            <?php if ($vehicaCurrentWidget->requireSelectPackage()) : ?>
                :payment-enabled="true"
            <?php else : ?>
                :payment-enabled="false"
            <?php endif; ?>
            <?php if (vehicaApp('recaptcha')) : ?>
                :re-captcha="true"
                re-captcha-key="<?php echo esc_attr(vehicaApp('settings_config')->getRecaptchaSite()); ?>"
            <?php endif; ?>
        >
            <div slot-scope="carForm">
                <form  id="multiStepForm" @submit.prevent="carForm.onUpdate">
                    <div class="tab active">
                    <?php if ($vehicaCurrentWidget->showSelectPackages()) : ?>
                        <?php get_template_part('templates/general/panel/select_package'); ?>
                    <?php endif; ?>
                    </div>
                  
                    <div class="vehica-car-form__section vehica-car-form__section--create-car">
                        <?php if (!is_user_logged_in()) : ?>
                            <div class="vehica-car-form__section__info-box">
                                <div class="vehica-car-form__section__info-box__inner">
                                    <?php if (vehicaApp('settings_config')->hasLoginPage()) : ?>
                                        <?php echo esc_html(vehicaApp('you_can_also_string')); ?>
                                        <a href="<?php echo esc_url(vehicaApp('settings_config')->getLoginPageUrl()); ?>">
                                            <?php echo esc_html(vehicaApp('log_in_string')); ?></a>
                                        <?php echo esc_html(vehicaApp('or_string')); ?>
                                        <a href="<?php echo esc_url(vehicaApp('settings_config')->getLoginPageUrl()); ?>">
                                            <?php echo esc_html(vehicaApp('register_string')); ?></a>
                                        <?php echo esc_html(vehicaApp('first_dot_string')); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="tab">
                            <div class="vehica-car-form__grid-wrapper">
                                <div class="vehica-car-form__grid">

                                    <?php if (vehicaApp('auto_title_fields')->isEmpty()) : ?>
                                        <div class="vehica-car-form__grid-element vehica-car-form__grid-element--row">
                                            <?php
                                            $vehicaNameField = $vehicaCurrentWidget->getNameField();
                                            $vehicaNameField->loadTemplate(); ?>
                                        </div>
										
                                    <?php endif; ?>

                                    <?php foreach ($vehicaCurrentWidget->getSingleValueFields() as $vehicaPanelField) :
                                        /* @var \Vehica\Panel\PanelField\PanelField $vehicaPanelField */
                                        if ($vehicaPanelField instanceof \Vehica\Panel\PanelField\DateTimePanelField || $vehicaPanelField instanceof \Vehica\Panel\PanelField\PricePanelField) :
                                            $vehicaPanelField->loadTemplate();
                                        else :?>
                                            <div class="vehica-car-form__grid-element vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaPanelField->getKey()); ?>">
                                                <?php echo esc_html($vehicaPanelField->loadTemplate()); ?>
												
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
						
                        <div class="tab">
                            <?php
                            $vehicaDescriptionPanelField = $vehicaCurrentWidget->getDescriptionField();
                            if ($vehicaDescriptionPanelField) : ?>
                                <div class="vehica-car-form__grid-element vehica-car-form__grid-element--row vehica-car-form-field__description">
                                    <?php $vehicaDescriptionPanelField->loadTemplate(); ?>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($vehicaCurrentWidget->getEmbedFields() as $vehicaPanelField) : ?>
                                <div class="vehica-car-form__grid-element vehica-car-form__grid-element--row vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaPanelField->getKey()); ?>">
                                    <?php $vehicaPanelField->loadTemplate(); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
					<div class="tab">
                    <?php foreach ($vehicaCurrentWidget->getGalleryFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php foreach ($vehicaCurrentWidget->getAttachmentsFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php foreach ($vehicaCurrentWidget->getLocationFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php foreach ($vehicaCurrentWidget->getMultiValueFields() as $vehicaPanelField) : ?>
                        <?php $vehicaPanelField->loadTemplate(); ?>
                    <?php endforeach; ?>

                    <?php if ($vehicaCurrentWidget->showPolicy()) : ?>
                        <div v-if="carForm.showErrors && !carForm.termsAccept" class="vehica-register-submit-notice">
                            <?php echo esc_html(vehicaApp('must_accept_privacy_policy_string')); ?>
                        </div>

                        <div class="vehica-checkbox vehica-checkbox--featured-big vehica-checkbox--policy">
                            <input
                                    :checked="carForm.termsAccept"
                                    @change="carForm.setTermsAccept"
                                    type="checkbox"
                                    id="checkbox_terms"
                            >

                            <label for="checkbox_terms">
                                <?php echo wp_kses_post($vehicaCurrentWidget->getPolicy()); ?>
                            </label>
                        </div>
                    <?php endif; ?>

                    <?php if ($vehicaCurrentWidget->showFeaturedCheckbox()) : ?>
                        <div style="display: none;" class="vehica-checkbox vehica-checkbox--featured-big vehica-checkbox--features-submit-listing">
                            <input
                                    type="checkbox"
                                    id="vehica-checkbox-featured"
                                    @change="carForm.setFeatured"
                                    :checked="carForm.car.featured"
                            >

                            <label for="vehica-checkbox-featured">
                                <?php echo esc_html(vehicaApp('featured_string')); ?>
                            </label>
                        </div>
                    <?php endif; ?>
					</div>
                    <!-- Payment Option -->
                     <template>
                            <div class=" test">
                            <?php foreach (vehicaApp('valid_payment_packages') as $vehicaPaymentPackage) :/* @var \Vehica\Panel\PaymentPackage $vehicaPaymentPackage */ ?>
                            <div
                                    v-if="carForm.buyPackageKey === '<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>'"
                                    class="vehica-package-buy-new"
                            >
                                <div class="vehica-package-buy-new__inner">
                                    <?php if (vehicaApp('settings_config')->isStripeEnabled() && \Vehica\Core\BaseCurrency::getSelected()) : ?>
                                        <div>
                                            <h3><?php echo esc_html(vehicaApp('pay_with_card_string')); ?></h3>
                                            
                                            <?php do_action('vehica/packages/beforeStripe'); ?>
                                            
                                            <vehica-stripe
                                                    key="stripe_<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>"
                                                    api-key="<?php echo esc_attr(vehicaApp('settings_config')->getStripeKey()); ?>"
                                                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_stripe_init')); ?>"
                                                    confirm-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_stripe_confirm')); ?>"
                                                    vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_stripe_init')); ?>"
                                                    :package-key="carForm.buyPackageKey"
                                                    in-progress-text="<?php echo esc_attr(vehicaApp('payment_processing_string')); ?>"
                                                    success-text="<?php echo esc_attr(vehicaApp('payment_processed_string')); ?>"
                                                    error-title="<?php echo esc_attr(vehicaApp('payment_error_string')); ?>"
                                                    error-text="<?php echo esc_attr(vehicaApp('payment_error_message_string')); ?>"
                                                    ok-text="<?php echo esc_attr(vehicaApp('ok_string')); ?>"
                                                    :collect-zip-code="<?php echo esc_attr(vehicaApp('settings_config')->stripeCollectZipCode() ? 'true' : 'false'); ?>"
                                                    :error-messages="<?php echo htmlspecialchars(json_encode([
                                                        'incomplete_number' => vehicaApp('incomplete_number_string'),
                                                    ])); ?>"
                                            >
                                                <div
                                                        slot-scope="stripeProps"
                                                        class="vehica-package-buy-new__stripe"
                                                >
                                                    <form id="vehica-payment-form">
                                                        <div id="vehica-card-errors"></div>
                                                        
                                                        <div id="vehica-card-element"></div>
                                                        <div class="vehica-submit-wrap">
                                                        <button 
                                                                id="vehica-submit"
                                                                class="vehica-stripe-button"
                                                        >
                                                            <span id="button-text"><?php echo esc_html(vehicaApp('pay_now_string')); ?>
                                                                (<?php echo esc_html($vehicaPaymentPackage->getDisplayPrice()); ?>
                                                                )</span>
                                                        </button>
                                                        </div>
                                                        <p id="card-error" role="alert"></p>
                                                    </form>
                                                    
                                                    <div class="vehica-package-buy-new__inner__info">
                                                        <?php echo esc_html(vehicaApp('pay_with_card_info_string')); ?>
                                                    </div>
                                                </div>
                                            </vehica-stripe>
                                        </div>
                                        
                                        <?php if ($vehicaIsPayPalEnabled) : ?>
                                            <div class="vehica-package-buy-new__or">
                                                <?php echo esc_html(vehicaApp('or_string')); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php if ($vehicaIsPayPalEnabled) : ?>
                                        <div>
                                            <vehica-paypal
                                                    key="paypal_<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>"
                                                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_paypal_init')); ?>"
                                                    confirm-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_paypal_confirm')); ?>"
                                                    :package-key="carForm.buyPackageKey"
                                                    vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_paypal_init')); ?>"
                                                    in-progress-text="<?php echo esc_attr(vehicaApp('payment_processing_string')); ?>"
                                                    success-text="<?php echo esc_attr(vehicaApp('payment_processed_string')); ?>"
                                            >
                                                <div slot-scope="payPal">
                                                    <div id="paypal-button-container"></div>
                                                </div>
                                            </vehica-paypal>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </template>
                    <!-- Payment block end -->

                    <div class="vehica-car-form__save-submit">
                        <button
                                class="vehica-button vehica-button--with-progress-animation"
                                :class="{'vehica-button--with-progress-animation--active': carForm.disabled, 'vehica-button--with-progress-animation--gallery-in-progress': carForm.inProgress}"
                                :disabled="carForm.disabled || carForm.inProgress"
                        >
                            <span class="vehica-button__text-initial"><?php echo esc_html(vehicaApp('submit_vehicle_string')); ?></span>
                            <span class="vehica-button__text-disabled"><i
                                        class="fas fa-file-import"></i> <?php echo esc_html(vehicaApp('uploading_files_please_wait_string')); ?>
                            </span>

                            <template>
                                <svg
                                        v-if="carForm.disabled"
                                        width="120"
                                        height="30"
                                        wviewBox="0 0 120 30"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="#fff"
                                >
                                    <circle cx="15" cy="15" r="15">
                                        <animate attributeName="r" from="15" to="15"
                                                 begin="0s" dur="0.8s"
                                                 values="15;9;15" calcMode="linear"
                                                 repeatCount="indefinite"/>
                                        <animate attributeName="fill-opacity" from="1" to="1"
                                                 begin="0s" dur="0.8s"
                                                 values="1;.5;1" calcMode="linear"
                                                 repeatCount="indefinite"/>
                                    </circle>
                                    <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                        <animate attributeName="r" from="9" to="9"
                                                 begin="0s" dur="0.8s"
                                                 values="9;15;9" calcMode="linear"
                                                 repeatCount="indefinite"/>
                                        <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                                 begin="0s" dur="0.8s"
                                                 values=".5;1;.5" calcMode="linear"
                                                 repeatCount="indefinite"/>
                                    </circle>
                                    <circle cx="105" cy="15" r="15">
                                        <animate attributeName="r" from="15" to="15"
                                                 begin="0s" dur="0.8s"
                                                 values="15;9;15" calcMode="linear"
                                                 repeatCount="indefinite"/>
                                        <animate attributeName="fill-opacity" from="1" to="1"
                                                 begin="0s" dur="0.8s"
                                                 values="1;.5;1" calcMode="linear"
                                                 repeatCount="indefinite"/>
                                    </circle>
                                </svg>
                            </template>
                        </button>
                    </div>
					<div class="progress">
        <span class="step active"></span>
        <span class="step"></span>
        <span class="step"></span>
    </div>
					<div class="buttons">
					<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
					<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
					<button type="submit" id="submitBtn" style="display: none;">Submit</button>
                    </div>
                </form>
            </div>
			 <!-- Navigation Buttons -->
        
        </vehica-car-form>

	
    

    <script>
        let currentTab = 0;
        showTab(currentTab);

        function showTab(n) {
            let tabs = document.querySelectorAll(".tab");
            let steps = document.querySelectorAll(".step");

            tabs.forEach(tab => tab.classList.remove("active"));
            tabs[n].classList.add("active");

            steps.forEach((step, index) => {
                step.classList.toggle("active", index <= n);
            });

            document.getElementById("prevBtn").style.display = (n === 0) ? "none" : "inline";
            document.getElementById("nextBtn").style.display = (n === tabs.length - 1) ? "none" : "inline";
            document.getElementById("submitBtn").style.display = (n === tabs.length - 1) ? "inline" : "none";
        }

        function nextPrev(n) {
            let tabs = document.querySelectorAll(".tab");
            //if (n === 1 && !validateForm()) return;

            currentTab += n;
            if (currentTab >= tabs.length) {
                document.getElementById("multiStepForm").submit();
                return;
            }
            showTab(currentTab);
        }

        function validateForm() {
            let tabs = document.querySelectorAll(".tab");
            let inputs = tabs[currentTab].querySelectorAll("input");

            for (let input of inputs) {
                if (input.value.trim() === "") {
                    alert("Please fill out all fields.");
                    //return false;
                }
            }
            return true;
        }
		  
		const words = ["Car", "Campervan", "Motorhome"];
		let index = 0;

		function changeText() {
			const textElement = document.getElementById("changing-text");
			textElement.style.opacity = 0; // Fade out effect
			setTimeout(() => {
				textElement.textContent = words[index];
				textElement.style.opacity = 1; // Fade in effect
				index = (index + 1) % words.length;
			}, 500);
		}

		setInterval(changeText, 2000);
    </script>


    </div>
</div>