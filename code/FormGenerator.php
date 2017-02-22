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

    public static function createSelectList($id, $label = "", $key_value_pairs, $autofocus = false, $multi_select = false) { ?>
        <div class="form-group" id="<?php echo $id; ?>-form-group">
            <label><?php echo $label; ?></label>
            <select class="form-control"
                name="<?php echo $id; ?>"
                id="<?php echo $id; ?>"
                <?php echo $autofocus ? "autofocus" : ""; ?>
                <?php echo $multi_select ? "multiple" : ""; ?> >
                <?php
                    foreach ($key_value_pairs as $key => $value) {
                        echo '<option value="' . $key . '">' . $value . "</options>";
                    }

                ?>
            </select>

        </div>
    <?php }

    public static function createUserList($id, $key_value_pairs, $selected_ids = null, $label = "") { ?>
        <div class="form-group" id="<?php echo $id; ?>-form-group">
            <label><?php echo $label; ?></label>
            <?php
                foreach ($key_value_pairs as $key => $value) {
                    echo "<div class=\"checkbox\"><label>";
                    echo "<input type=\"checkbox\" id=\"" . $key . "\"";
                    if ($selected_ids != null) {
                        if (in_array($key, $selected_ids)) {
                            echo "checked";
                        }
                    }
                    echo "/>";
                    echo htmlspecialchars($value);
                    echo "</label></div>";
                }
            ?>
        </div>
    <?php }
}
