$(function() {
  var uri = 'http://www.cems.uwe.ac.uk/~slacey/convert';
  var uri = 'http://cc.i7.stevelacey.net';

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

          $.ajax({
            url: uri + '/' + $(this).find('.amount').val() + '/from/' + $(this).find('.from').val() + '/to/' + $(this).find('.to').val() + '/json',
            dataType: 'jsonp',
            success: function(json) {
              $(form).find('span').text(json.result);
            }
          });

          event.preventDefault();
        });
      });
    }
  });
});