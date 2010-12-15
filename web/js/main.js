$(function() {
  var uri = 'http://www.cems.uwe.ac.uk/~slacey/convert';

  $.ajax({
    url: uri + '/currencies.json',
    dataType: 'jsonp',
    success: function(currencies) {
      $('.currency-converter').each(function() {
        $(this)
          .append($('<form/>')
            .append($('<input/>', {name: 'amount', 'class': 'amount', type: 'text'}))
            .append($('<select/>', {name: 'from', 'class': 'from'}))
            .append($('<select/>', {name: 'to', 'class': 'to'}))
            .append($('<input/>', {type: 'submit', value: 'Convert'}))
            .append($('<span/>'))
          )
        ;

        for(key in currencies) {
          $(this).find('select').append($('<option/>', {text: currencies[key] + ' (' + key + ')', value: key}));
        }
        
        $(this, 'form').submit(function(event) {
          var form = this;
          $(form).find('span').html('<img src="' + uri + '/images/prakash.jpg" alt="Prakash Loader"/>');

          $.ajax({
            url: uri + '/' + $(this).find('.amount').val() + '/from/' + $(this).find('.from').val() + '/to/' + $(this).find('.to').val() + '/json',
            dataType: 'jsonp',
            success: function(json) {
              if(!json.error) {
                $(form).find('span').text(json.from.amnt + ' ' + json.from.name + ' is ' + Math.round(json.to.amnt * 100) / 100 + ' ' + json.to.name);
              } else {
                $(form).find('span').text(json.error.message);
              }
            },
            error: function() {
              $(form).find('span').text('Error');
            }
          });

          event.preventDefault();
        });
      });
    }
  });
});