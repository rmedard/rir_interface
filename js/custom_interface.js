(function ($, Drupal) {
  Drupal.behaviors.rir_interface = {
    attach: function (context, settings) {

      const priceField = $(context).find('input#edit-field-advert-price-0-value');
      numbifyField(priceField);

      const startingValueField = $(context).find('input#edit-field-advert-bid-starting-value-0-value');
      numbifyField(startingValueField);

      const securityAmountField = $(context).find('input#edit-field-advert-bid-security-amount-0-value');
      numbifyField(securityAmountField);

      function numbifyField(field) {
        if (field && field.length) {
          field.attr('type', 'text');
          field.number(true, 0);
          $(context).find('form#node-advert-edit-form').submit(function () {
            const value = field.val();
            field.attr('type', 'number');
            field.val(value);
          });
          $(context).find('form#node-advert-form').submit(function () {
            const value = field.val();
            field.attr('type', 'number');
            field.val(value);
          });
        }
      }
    }
  }
})(jQuery, Drupal);
