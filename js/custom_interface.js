(function ($, Drupal) {
  Drupal.behaviors.rir_interface = {
    attach: function (context, settings) {

      const priceField = $(context).find('input#edit-field-advert-price-0-value');
      if (priceField && priceField.length) {
        priceField.attr('type', 'text');
        priceField.number(true, 0);
        $(context).find('form#node-advert-edit-form').submit(function() {
          const value  = priceField.val();
          priceField.attr('type', 'number');
          priceField.val(value);
        });
      }
    }
  }
})(jQuery, Drupal);
