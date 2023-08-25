<script setup>

</script>

<template>
    <div class="w-full max-h-screen overflow-y-auto uni-main-container">
        <div class="w-full p-8 mx-auto md:p-10 xl:p-5 unify-box-content">

            <form action="" method="post" id="frmuniform" enctype="multipart/form-data">
                <div class="uni-heading-inline">
                    <h1 class="uni-heading -indent-[9999px] absolute m-0 p-0">Unifymedsupply</h1>
                    <div class="flex items-center justify-center px-3 my-3">
                        <img :src="ajv" alt="Unifymedsupply" srcset="" class="block w-28">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_first_name" class="inline-block mb-1 text-sm font-medium text-gray-600">Firstname</label>
                        <input type="text" name="s_first_name" id="s_first_name" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_last_name" class="inline-block mb-1 text-sm font-medium text-gray-600">Lastname</label>
                        <input type="text" name="s_last_name" id="s_last_name" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    </div>
                </div>

                <!-- <?php if (!empty($insurance_coverages)) { ?>
                    <div class="relative z-0 w-full mb-6 group">
                        <label class="relative inline-flex items-center mb-4 cursor-pointer" for="s_insurance">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="s_insurance" id="s_insurance" class="sr-only peer focus:outline-none">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-900">Do you already have health insurance?</span>
                        </label>
                        <div class="relative hidden w-full" id="insurance_info">
                            <div class="relative z-0 w-full mb-6 group">
                                <label class="inline-block mb-1 text-sm font-medium text-gray-600">Choose the insurance plan you have joined:</label>
                                <div class="flex flex-wrap items-center">
                                    <?php
                                    foreach ($insurance_coverages as $key => $coverage) {
                                        if ($coverage['status'] != 'on') continue; // Skip off coverage
                                        echo '<div class="flex items-center mb-2 mr-2">';
                                        echo '<input type="checkbox" name="s_coverages[]" id="s_coverages_' . $coverage['id'] . '" value="' . $coverage['id'] . '" class="cursor-pointer">';
                                        echo '<label for="s_coverages_' . $coverage['id'] . '" class="pl-3 pr-1 text-sm font-medium text-gray-600 cursor-pointer">' . $coverage['name'] . '</label>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?> -->

                <div class="grid md:grid-cols-3 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_doctor_name" class="inline-block mb-1 text-sm font-medium text-gray-600">Doctor Name</label>
                        <input type="text" name="s_doctor_name" id="s_doctor_name" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_weight" class="inline-block mb-1 text-sm font-medium text-gray-600">Weight</label>
                        <input type="text" name="s_weight" id="s_weight" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_weight" class="inline-block mb-1 text-sm font-medium text-gray-600">Height</label>
                        <input type="text" name="s_height" id="s_height" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_phone" class="inline-block mb-1 text-sm font-medium text-gray-600">Phone</label>
                        <input type="text" name="s_phone" id="s_phone" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_dob" class="inline-block mb-1 text-sm font-medium text-gray-600">Date of Birth</label>
                        <input type="text" readonly pattern="\d{1,2}-\d{1,2}-\d{4}" placeholder="mm-dd-YYYY" name="s_dob" id="s_dob" class="block date-picker w-full px-0 py-1 text-base font-bold text-gray-900 uppercase !bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_gender" class="inline-block mb-1 text-sm font-medium text-gray-600">Gender</label>
                        <select class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" name="s_gender" id="s_gender" required="">
                            <option value="0">Male</option>
                            <option value="1">Female</option>
                        </select>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_email" class="inline-block mb-1 text-sm font-medium text-gray-600">Email</label>
                        <input type="email" name="s_email" id="s_email" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                </div>


                <div class="relative z-0 w-full mb-6 group">
                    <label for="s_address" class="inline-block mb-1 text-sm font-medium text-gray-600">Address</label>
                    <input type="text" name="s_address" id="s_address" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_apt" class="inline-block mb-1 text-sm font-medium text-gray-600">Apt</label>
                        <input type="text" name="s_apt" id="s_apt" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_zip" class="inline-block mb-1 text-sm font-medium text-gray-600">ZIP</label>
                        <input type="text" name="s_zip" id="s_zip" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_city" class="inline-block mb-1 text-sm font-medium text-gray-600">City</label>
                        <input type="text" name="s_city" id="s_city" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <label for="s_state" class="inline-block mb-1 text-sm font-medium text-gray-600">State</label>
                        <div class="relative w-full">
                            <!-- <?php
                            $state_list = uniState();
                            ?>
                            <div class="relative w-full">
                                <select name="s_state" id="s_state" data-placeholder="Select a state" class="block w-full px-0 py-1 text-base font-bold text-gray-900 uppercase bg-white border-0 border-b-2 !border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                    <option></option>
                                    <?php foreach ($state_list as $key => $value) { ?>
                                        <option value="<?php echo $key ?>"><?php echo $value ?> (<?php echo $key ?>)</option>
                                    <?php } ?>
                                </select>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- <?php
                // IF logged in 
                if (is_user_logged_in()) {
                    echo '<input type="hidden" name="s_sale_user" id="s_sale_user" value="' . get_current_user_id() . '" />';
                } else {
                    $user = get_user_by('id', $getCurrenKey);
                    if (!empty($getCurrenKey) && $user) {
                        echo '<input type="hidden" name="s_sale_user" id="s_sale_user" value="' . $getCurrenKey . '" />';
                    } else {
                        $get_default_sale = get_option('s_sale_default');
                        echo '<input type="hidden" name="s_sale_user" id="s_sale_user" value="' . $get_default_sale . '" />';
                    }
                }
                ?> -->

                <div class="relative z-0 w-full mb-3 group">
                    <label class="text-sm text-gray-500 duration-300 -translate-y-6 -z-10">Signature</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <!-- <?php if ($smallSize) { ?>
                        <canvas id="signature-pad" height="250" width="300" class="border border-gray-300"></canvas>
                    <?php } else { ?>
                        <canvas id="signature-pad" height="500" width="600" class="border border-gray-300"></canvas>
                    <?php } ?> -->
                    <button id="clear-signature" class="absolute z-20 px-2 py-1 text-sm text-white bg-gray-400 border-0 rounded-sm top-2 left-2">Clear</button>
                    <input type="file" name="s_uploadSignature" id="s_uploadSignature" class="hidden">
                </div>

                <div class="relative z-0 w-full mb-3 group">
                    <label for="s_note" class="text-sm text-gray-500 duration-300 -translate-y-6 -z-10">Your request</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <textarea class="ckeditor" name="s_note" id="s_note"></textarea>
                </div>

                <button id="submitForm" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
            </form>

        </div>
    </div>
</template>
<style scoped></style>
