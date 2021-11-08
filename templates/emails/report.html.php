Email HTML
Title: <?php
if (isset($title)): ?><?= $title ?? ''; ?><?php
endif; ?>
Fields: <?php
if (isset($fields)): ?><?= $fields['description'] ?? ''; ?><?php
endif; ?>
