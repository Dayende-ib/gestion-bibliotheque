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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Books')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <head>
        <style>
            .form-inline {
                margin-bottom: 20px;
            }

            .form-inline input[type="text"] {
                width: 50%;
                padding: 10px;
                font-size: 16px;
            }

            .form-inline button[type="submit"] {
                padding: 10px 20px;
                font-size: 16px;
                background-color: #337ab7;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .form-inline button[type="submit"]:hover {
                background-color: #23527c;
            }
        </style>
         
        <style>
            .custom-card {
                border: 1px solid #ddd;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                height: 400px; /* Fixed height */
                width: 100%; /* Fixed width */
                display: flex;
                flex-direction: column;
                position: relative;
                overflow: hidden;
            }

            .custom-card .card-title {
                font-size: 1.25rem;
                font-weight: bold;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 1; /* Number of lines to show */
                line-clamp: 1;
                overflow: hidden;
            }

            .custom-card .card-text {
                font-size: 1rem;
                color: #555;
                max-height: 80px; /* Initial max height */
                overflow: hidden;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2; /* Number of lines to show */
                line-clamp: 2;
            }

            .custom-card img {
                max-width: 100%;
                height: 60%; /* Fixed height */
                border-bottom: 1px solid #ddd;
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                object-fit: cover;
                transition: height 0.7s, transform 0.7s;
            }
            .custom-card:hover img {
                height: auto; /* Fixed height on hover */
                width: 100%;
                transform: scale(0.9);
                
            }

            .card-body {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .card-actions {
                position: absolute;
                top: 10px;
                right: 10px;
                display: none;
            }

            .custom-card:hover .card-actions {
                display: block;
            }

            .card-actions a, button {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .card-author {
                bottom: 10px;
                right: 10px;
                font-weight: bold;
                color: #007bff; /* Primary color */
            }
        </style>
    </head>
    
    <div class="container mt-4">
        <?php if(Auth::user()->role == 'admin'): ?>
            <a href="<?php echo e(route('books.create')); ?>" class="btn btn-success mb-3">Add book</a>
        <?php endif; ?>
        
        <?php if(Session::get('success')): ?>
            <div class="alert alert-success" style="background-color: green; color: white;">
                <?php echo e(Session::get('success')); ?>

            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card custom-card">
                        <?php if($book->image != null): ?>
                            <img src="<?php echo e(asset($book->image)); ?>" alt="<?php echo e($book->title); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('build/images/default-book-cover.png')); ?>" alt="Default Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($book->title); ?></h5>
                            <p class="card-text"><?php echo e($book->description); ?></p>
                            <p class="card-author mt-2 font-bold text-primary text-right"><?php echo e($book->author); ?></p>
                            <div class="card-actions">
                                <?php if(Auth::user()->role == 'admin'): ?>
                                    <a href="<?php echo e(route('books.edit', $book->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="<?php echo e(route('books.destroy', $book->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm"> <i class="fa "></i>Delete</button>
                                    </form>
                                <?php else: ?>
                                    <?php if($book->status == 'Borrowed'): ?>
                                        <a href="#" class="btn btn-secondary btn-sm disabled">Borrowed</a>
                                    <?php else: ?>
                                        <a href="#" onclick="checkMembership(<?php echo e(Auth::user()->member ? 'true' : 'false'); ?>, <?php echo e($book->id); ?>)" class="btn btn-primary btn-sm">Borrow</a>
                                        <a href="#" class="btn btn-secondary btn-sm">Return</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-sm-12">
                    <div class="alert alert-info">No books to show</div>
                </div>
            <?php endif; ?>
        </div>

        

    
    

    <!-- Add this modal dialog in the body of index.blade.php -->
<div id="membershipModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Become a Member</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You need to be a member to borrow a book. Would you like to become a member?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="<?php echo e(route('members.create')); ?>" class="btn btn-primary">Become a Member</a>
            </div>
        </div>
    </div>
</div>

<script>
    function checkMembership(isMember, bookId) {
        if (isMember) {
            window.location.href = '/loans/create/' + bookId;
        } else {
            $('#membershipModal').modal('show');
        }
    }

    $(document).ready(function() {
    var timeout;
    $('#search-input').on('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            var search = $('#search-input').val();
            $.ajax({
                type: 'GET',
                url: '/books/search',
                data: {search: search},
                success: function(data) {
                    $('#books-table').html(data);
                }
            });
        }, 500);
    });
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
<?php endif; ?><?php /**PATH C:\Users\PC\Desktop\gestion-bibliotheque\laravel-backend\resources\views/books/index.blade.php ENDPATH**/ ?>