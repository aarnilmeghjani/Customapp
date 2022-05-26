<?php $__env->startComponent('mail::message'); ?>

    <?php echo e($data['heading']); ?>


<?php $__env->startComponent('mail::table'); ?>
    | Details       | Count         |
    | :--------- | :-------------: |
    | Total Customer | <?php echo e($data['totalDealer']); ?> |
    | Successfully Imported Customer | <?php echo e($data['totalSuccessDealer']); ?> |
    | Total Failed Customer | <?php echo e($data['totalFailDealer']); ?> |
<?php echo $__env->renderComponent(); ?>

Thanks,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/krunal-planetx/Work/Web/larave-vue-app/custom-app/resources/views/email/import/data.blade.php ENDPATH**/ ?>