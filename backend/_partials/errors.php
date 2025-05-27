<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <div id="errorContainer">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>