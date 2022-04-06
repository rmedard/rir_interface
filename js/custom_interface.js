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
        $(context).find('form#node-advert-form').submit(function() {
          const value  = priceField.val();
          priceField.attr('type', 'number');
          priceField.val(value);
        });
      }

      const startingValueField = $(context).find('input#edit-field-advert-bid-starting-value-0-value');
      if (startingValueField && startingValueField.length) {
        startingValueField.attr('type', 'text');
        startingValueField.number(true, 0);
        $(context).find('form#node-advert-edit-form').submit(function() {
          const value  = startingValueField.val();
          startingValueField.attr('type', 'number');
          startingValueField.val(value);
        });
        $(context).find('form#node-advert-form').submit(function() {
          const value  = startingValueField.val();
          startingValueField.attr('type', 'number');
          startingValueField.val(value);
        });
      }

      const securityAmountField = $(context).find('input#edit-field-advert-bid-security-amount-0-value');
      if (securityAmountField && securityAmountField.length) {
        securityAmountField.attr('type', 'text');
        securityAmountField.number(true, 0);
        $(context).find('form#node-advert-edit-form').submit(function() {
          const value  = securityAmountField.val();
          securityAmountField.attr('type', 'number');
          securityAmountField.val(value);
        });
        $(context).find('form#node-advert-form').submit(function() {
          const value  = securityAmountField.val();
          securityAmountField.attr('type', 'number');
          securityAmountField.val(value);
        });
      }
    }
  }
})(jQuery, Drupal);
