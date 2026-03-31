<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Visa Tips - <?php echo e(config('app.name', 'Bookshop')); ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" href="/favicon.ico" sizes="any">
    </head>
    <body class="antialiased">
        <?php if (isset($component)) { $__componentOriginalbb0843bd48625210e6e530f88101357e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb0843bd48625210e6e530f88101357e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.flash-message','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flash-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbb0843bd48625210e6e530f88101357e)): ?>
<?php $attributes = $__attributesOriginalbb0843bd48625210e6e530f88101357e; ?>
<?php unset($__attributesOriginalbb0843bd48625210e6e530f88101357e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbb0843bd48625210e6e530f88101357e)): ?>
<?php $component = $__componentOriginalbb0843bd48625210e6e530f88101357e; ?>
<?php unset($__componentOriginalbb0843bd48625210e6e530f88101357e); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal352d085fcf25f89da5d9c58864271205 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal352d085fcf25f89da5d9c58864271205 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.customer-navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('customer-navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal352d085fcf25f89da5d9c58864271205)): ?>
<?php $attributes = $__attributesOriginal352d085fcf25f89da5d9c58864271205; ?>
<?php unset($__attributesOriginal352d085fcf25f89da5d9c58864271205); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal352d085fcf25f89da5d9c58864271205)): ?>
<?php $component = $__componentOriginal352d085fcf25f89da5d9c58864271205; ?>
<?php unset($__componentOriginal352d085fcf25f89da5d9c58864271205); ?>
<?php endif; ?>

        <!-- Hero Section with S-Wave -->
        <section class="relative pt-32 pb-20 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="<?php echo e(asset('visa.jpg')); ?>" alt="Visa Tips" class="w-full h-full object-cover object-[center_70%]">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/80 to-purple-900/80"></div>
            </div>
            
            <!-- Hero Content -->
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center">
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                        Visa Interview Tips
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-2xl mx-auto">
                        Essential advice to help you succeed in your visa interview.
                    </p>
                    <a href="#content" class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section id="content" class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-6">
                <!-- Introduction -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Preparing for Your Visa Interview</h2>
                    <p class="text-xl text-gray-600">Practical guides to help students and travelers understand visa interviews, avoid common mistakes, and answer visa officer questions with confidence.</p>
                </div>

                <!-- Tips Grid -->
                <div class="space-y-8">
                    <!-- Tip 1 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                1
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Be Clear About Your Study Plan</h3>
                                <p class="text-gray-700">Visa officers want to understand why you chose your program and school. Your explanation should be clear and connected to your career goals.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 2 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                2
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Know Your School and Program</h3>
                                <p class="text-gray-700">You should be able to explain:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Why you chose that university</li>
                                    <li>What the program teaches</li>
                                    <li>How it helps your future career</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 3 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                3
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Be Honest and Confident</h3>
                                <p class="text-gray-700">Always answer questions truthfully and confidently. Visa officers are trained to notice when someone is unsure or inconsistent.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 4 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                4
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Understand Your Financial Support</h3>
                                <p class="text-gray-700">Be ready to clearly explain:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Who is sponsoring you</li>
                                    <li>What they do for work</li>
                                    <li>How your education will be funded</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 5 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                5
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Keep Your Answers Short and Direct</h3>
                                <p class="text-gray-700">Visa interviews are usually very short. Avoid long explanations and answer the question directly.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 6 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                6
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Prepare for Common Questions</h3>
                                <p class="text-gray-700">Some common questions include:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Why do you want to study in the U.S.?</li>
                                    <li>Why this school?</li>
                                    <li>What will you do after graduation?</li>
                                </ul>
                                <p class="text-gray-700 mt-2">Practicing your answers helps you stay confident.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 7 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                7
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Organize Your Documents</h3>
                                <p class="text-gray-700">Make sure your important documents are ready, such as:</p>
                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                    <li>Passport</li>
                                    <li>I-20 form</li>
                                    <li>Financial documents</li>
                                    <li>Admission letter</li>
                                </ul>
                                <p class="text-gray-700 mt-2">Having them organized shows preparation.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip 8 -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                                8
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Stay Calm During the Interview</h3>
                                <p class="text-gray-700">Even if the officer asks unexpected questions, stay calm and answer respectfully.</p>
                                <p class="text-gray-700 mt-2 font-medium">Confidence matters.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center mt-16">
                    <p class="text-gray-600 mb-6">Want more detailed guidance? Check out our books for comprehensive visa preparation.</p>
                    <a href="<?php echo e(route('home')); ?>#store" class="inline-block px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity">
                        Browse Our Books
                    </a>
                </div>
            </div>
        </section>

      <?php if (isset($component)) { $__componentOriginal0ce8c361df782acd2f488d363e827ff6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0ce8c361df782acd2f488d363e827ff6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.customer-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('customer-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0ce8c361df782acd2f488d363e827ff6)): ?>
<?php $attributes = $__attributesOriginal0ce8c361df782acd2f488d363e827ff6; ?>
<?php unset($__attributesOriginal0ce8c361df782acd2f488d363e827ff6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0ce8c361df782acd2f488d363e827ff6)): ?>
<?php $component = $__componentOriginal0ce8c361df782acd2f488d363e827ff6; ?>
<?php unset($__componentOriginal0ce8c361df782acd2f488d363e827ff6); ?>
<?php endif; ?>
    </body>
</html>
<?php /**PATH C:\Users\enter\Herd\bookshop\resources\views/visa-tip.blade.php ENDPATH**/ ?>