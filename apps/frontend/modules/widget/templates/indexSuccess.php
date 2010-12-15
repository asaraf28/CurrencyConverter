<h2>Demo</h2>

<div class="currency-converter"></div>

<h2>Installation</h2>

<p>This is a basic example of a basic jQuery widget for the currency converter which can be used on other websites.</p>
<p>To add the widget to your website simply append &lt;div class="currency-converter"&gt;&lt;/div&gt; to your page wherever you want the converter to appear, and implement the JavaScript below or hosted at <a href="http://www.cems.uwe.ac.uk/~slacey/convert/js/main.js">http://www.cems.uwe.ac.uk/~slacey/convert/js/main.js</a>.</p>

<h3>JavaScript</h3>

<pre>
  $(function() {
    var uri = 'http://www.cems.uwe.ac.uk/~slacey/convert';

    $.ajax({
      url: uri + '/currencies.json',
      dataType: 'jsonp',
      success: function(currencies) {
        $('.currency-converter').each(function() {
          $(this)
            .append($('&lt;form/&gt;')
              .append($('&lt;input/&gt;', {name: 'amount', 'class': 'amount', type: 'text'}))
              .append($('&lt;select/&gt;', {name: 'from', 'class': 'from'}))
              .append($('&lt;select/&gt;', {name: 'to', 'class': 'to'}))
              .append($('&lt;input/&gt;', {type: 'submit', value: 'Convert'}))
              .append($('&lt;span/&gt;'))
            )
          ;

          for(key in currencies) {
            $(this).find('select').append($('&lt;option/&gt;', {text: currencies[key] + ' (' + key + ')', value: key}));
          }

          $(this, 'form').submit(function(event) {
            var form = this;
            $(form).find('span').html('&lt;img src="' + uri + '/images/prakash.jpg" alt="Prakash Loader"/&gt;');

            $.ajax({
              url: uri + '/' + $(this).find('.amount').val() + '/from/' + $(this).find('.from').val() + '/to/' + $(this).find('.to').val() + '/json',
              dataType: 'jsonp',
              success: function(json) {
                $(form).find('span').text(json.from.amnt + ' ' + json.from.name + ' is ' + Math.round(json.to.amnt * 100) / 100 + ' ' + json.to.name);
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
</pre>