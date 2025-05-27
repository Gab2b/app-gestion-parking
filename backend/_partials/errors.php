<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <div id="errorContainer" class="alert alert-danger d-none">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>