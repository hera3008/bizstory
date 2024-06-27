"use strict";

// Class definition
var KTSigninGeneral = function() {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleValidation = function(e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
			form,
			{
				fields: {					
					'param[mem_id]': {
                        validators: {
                            regexp: {
                                //regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                //message: '값이 유효한 이메일 주소가 아닙니다.',
                            },
							notEmpty: {
								message: '로그인 아이디를 입력해주세요'
							}
						}
					},
                    'param[mem_pwd]': {
                        validators: {
                            notEmpty: {
                                message: '비밀번호가 필요합니다'
                            }
                        }
                    } 
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
				}
			}
		);	
    }

    var handleSubmitDemo = function(e) {
        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click 
                    submitButton.disabled = true;
                    
                     /*
                    // Simulate ajax request
                    setTimeout(function() {
                        // Hide loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;

                        // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                       
                        Swal.fire({
                            text: "성공적으로 로그인했습니다!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "확인",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) { 
                                form.querySelector('[name="param[mem_pwd]"]').value= "";
                                form.querySelector('[name="param[mem_pwd]"]').value= "";  
                                                              
                                //form.submit(); // submit form
                                var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                if (redirectUrl) {
                                    location.href = redirectUrl;
                                }
                            }
                        });
                    }, 2000); 
                    */  						
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "오류가 감지된 것 같습니다. 다시 시도해 주세요.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "확인",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
		});
    }

    var handleSubmitAjax = function(e) {
        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Hide loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;
                                        
                    // Check axios library docs: https://axios-http.com/docs/intro 
                    axios.post('/bizstory/member/login_ok.php', $('#kt_sign_in_form').serialize()).then(function (response) {
                        //console.log(response.data);

                        if (response.data.success_chk == 'Y') {
                            form.querySelector('[name="param[mem_id]"]').value= "";
                            form.querySelector('[name="param[mem_pwd]"]').value= "";  

                            setTimeout(function() {
                                // Hide loading indication
                                submitButton.removeAttribute('data-kt-indicator');
        
                                // Enable button
                                submitButton.disabled = false;
        
                                // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                               
                                Swal.fire({
                                    text: "성공적으로 로그인했습니다!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "확인",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }                               
                                }).then(function (result) {
                                    if (result.isConfirmed) { 
                                        form.querySelector('[name="param[mem_id]"]').value= "";
                                        form.querySelector('[name="param[mem_pwd]"]').value= "";  
                                                                      
                                        //form.submit(); // submit form
                                        var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                        if (redirectUrl) {
                                            location.href = redirectUrl;
                                        }
                                    }
                                });
                               
                            }, 2000); 

                        } else {
                            // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            Swal.fire({
                                html: response.data.error_string,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "확인",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    }).catch(function (error) {
                        Swal.fire({
                            text: "오류가 감지된 것 같습니다. 다시 시도해 주세요..1",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "확인",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    });
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "오류가 감지된 것 같습니다. 다시 시도해 주세요..2",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "확인",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
		});
    }

    // Public functions
    return {
        // Initialization
        init: function() {
            form = document.querySelector('#kt_sign_in_form');
            submitButton = document.querySelector('#kt_sign_in_submit');
            
            handleValidation();
            handleSubmitDemo(); // used for demo purposes only, if you use the below ajax version you can uncomment this one
            handleSubmitAjax(); // use for ajax submit
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTSigninGeneral.init();
});
