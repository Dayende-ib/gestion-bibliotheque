<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="container mt-5">
        <h2 class="mb-4 h2 text-center">ADD NEW MEMBER</h2>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('members.store')); ?>" method="POST" class="bg-light-subtle p-5 rounded shadow-sm">
            <?php echo csrf_field(); ?>
            <div class="row mb-3">
                <?php if(Auth::user()->role == 'admin'): ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">Select a user</option>
                                <!-- list of users -->
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->lastname); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-label">User</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo e(Auth::user()->id); ?>" readonly hidden>
                            <input type="text" class="form-control" value="<?php echo e(Auth::user()->lastname); ?> <?php echo e(Auth::user()->firstname); ?>" readonly>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Fill here your phone" required>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Fill here your address" required>
            </div>
            <div class="form-group mb-3">
                <label for="membership_number" class="form-label">Membership Number</label>
                <input type="text" class="form-control" id="membership_number" name="membership_number" value="<?php echo e($membershipNumber); ?>" readonly>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="join_date" class="form-label">Join Date</label>
                        <input type="datetime-local" class="form-control" id="join_date" name="join_date" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <select class="form-control" id="status" name="status" required hidden>
                    <option value="Active">Active</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var now = new Date();
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var day = ('0' + now.getDate()).slice(-2);
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            var formattedDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
            document.getElementById('join_date').value = formattedDateTime;
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\PC\Desktop\gestion-bibliotheque\laravel-backend\resources\views/members/create.blade.php ENDPATH**/ ?>