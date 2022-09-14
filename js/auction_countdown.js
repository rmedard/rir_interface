(function () {
  Drupal.behaviors.rir_interface = {
    attach: function (context, settings) {
      if (settings.viewMode !== undefined) {
        if (settings.viewMode === 'view') {
          if (settings.auctions !== undefined && Array.isArray(settings.auctions)) {
            if (settings.auctions !== undefined && Array.isArray(settings.auctions)) {
              settings.auctions.forEach((item, index, arr) => {
                const advert = document.querySelector("article[data-history-node-id='" + item.nid + "']");
                const countDownSpan = advert.querySelector('#countdown');
                const expirationDate = moment.tz(item.expiration, "UCT");
                countDown(countDownSpan, expirationDate);
              });
            }
          }
        } else if (settings.viewMode === 'full' && settings.expiration !== undefined) {
          const expirationDate = moment.tz(settings.expiration, "UCT");
          const countDownSpan = document.getElementById('countdown');
          countDown(countDownSpan, expirationDate);
        }
      }

      function countDown(countDownSpan, expirationDate) {
        countDownSpan.classList.add('text-danger', 'fw-bold');
        const counter = setInterval(function () {
          const distance = expirationDate - new Date().getTime();
          const days = daysFormat(Math.floor(distance / (1000 * 60 * 60 * 24)));
          const hours = integerFormat(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)), 2, 'h');
          const minutes = integerFormat(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)), 2, 'm');
          const seconds = integerFormat(Math.floor((distance % (1000 * 60)) / 1000), 2, 's');
          countDownSpan.innerHTML = (days + " " + hours + " " + minutes + " " + seconds).trim();
          if (distance < 0) {
            clearInterval(counter);
            countDownSpan.innerHTML = "Closed";
          }
        }, 1000);
      }

      function integerFormat(number, size, unity) {
        number = number.toString();
        while (number.length < size) number = "0" + number;
        return number + unity;
      }

      function daysFormat(number) {
        return number === 0 ? '' : number + 'd';
      }
    }
  }
})();
