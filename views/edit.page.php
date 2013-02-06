<form method="POST" action="<?= $post_url ?>">
<input id="title" type="text" name="title" value="<?= htmlspecialchars($title) ?>">
<textarea id="edit_content" name="content"><?= htmlspecialchars($content) ?></textarea>
<input type="submit" class="btn" value="Submit">
</form>