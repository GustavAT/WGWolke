<?php

// todo refactor: duplicate code
class FormGenerator {

    public static function createTextField($id, $placeholder = "", $autofocus = false) { ?>
        <div class="form-group" id="<?php echo $id; ?>-form-group">
            <input class="form-control"
                placeholder="<?php echo $placeholder; ?>"
                id="<?php echo $id; ?>"
                name="<?php echo $id; ?>"
                type="text"
                <?php echo $autofocus ? "autofocus" : ""; ?>
                maxlength="50">
        </div>
    <?php }

    public static function createTextarea($id, $rows = 3, $placeholder = "", $autofocus = false, $maxlength = 500) { ?>
        <div class="form-group" id="<?php echo $id; ?>-form-group">
            <textarea class="form-control"
                rows="<?php echo $rows; ?>"
                placeholder="<?php echo $placeholder; ?>"
                id="<?php echo $id; ?>"
                name="<?php echo $id; ?>"
                maxlength="<?php echo $maxlength; ?>"></textarea>
        </div>
    <?php }

    public static function createEmailField($id, $placeholder = "", $autofocus = false) { ?>
        <div class="form-group input-group" id="<?php echo $id; ?>-form-group">
            <span class="input-group-addon">@</span>
            <input class="form-control"
                placeholder="<?php echo $placeholder; ?>"
                id="<?php echo $id; ?>"
                name="<?php echo $id; ?>"
                type="email"
                <?php echo $autofocus ? "autofocus" : ""; ?>>
        </div>
    <?php }

    public static function createPasswordField($id, $placeholder = "", $autofocus = false) { ?>
        <div class="form-group" id="<?php echo $id; ?>-form-group">
            <input class="form-control"
                placeholder="<?php echo $placeholder; ?>"
                id="<?php echo $id; ?>"
                name="<?php echo $id; ?>"
                type="password"
                <?php echo $autofocus ? "autofocus" : ""; ?>
                maxlength="32">
        </div>
    <?php }
}
