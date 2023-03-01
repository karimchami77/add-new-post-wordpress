<div class="mon-plugin-gutenberg-form">
  <h3><?php echo esc_html( $title ); ?></h3>
  <div class="form-group">
    <label for="<?php echo esc_attr( $titleFieldId ); ?>"><?php echo esc_html( $titleFieldLabel ); ?></label>
    <input type="text" class="form-control" id="<?php echo esc_attr( $titleFieldId ); ?>" name="<?php echo esc_attr( $titleFieldName ); ?>" required>
    <div class="invalid-feedback"></div>
  </div>
  <div class="form-group">
    <label for="<?php echo esc_attr( $contentFieldId ); ?>"><?php echo esc_html( $contentFieldLabel ); ?></label>
    <textarea class="form-control" id="<?php echo esc_attr( $contentFieldId ); ?>" name="<?php echo esc_attr( $contentFieldName ); ?>" required></textarea>
    <div class="invalid-feedback"></div>
  </div>
  <button type="submit" class="btn btn-primary"><?php echo esc_html( $submitButtonText ); ?></button>
</div>
