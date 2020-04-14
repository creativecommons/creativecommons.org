function base_convert(n, base) {
    var dictionary = '0123456789abcdefghijklmnopqrstuvwxyz';
    var m = n.toString(base);
    var digits = [];
    for (var i = 0; i < m.length; i++) {
        digits.push(dictionary.indexOf(m.charAt(i)) - 1);
    }
    return digits;
}
var letters = {
    'arabic': {
        'lower': 'أبجدھوزحطيكلمنسعفصقرشتثخذضظغ',
        'upper': 'أبجدھوزحطيكلمنسعفصقرشتثخذضظغ'
    },
	'indic': {
        'lower': '١٢٣٤٥٦٧٨٩',
        'upper': ''
    }
}
$( document ).ready(function() {
   $( "ul, ol" ).each(function() {
  			if (!(results = $(this).prop('class').match(/(upper|lower)-([a-z]+)/i))) return;
			var characters = letters[results[2]][results[1]];
			$('> li', this).each(function(index, element) {
        var number = '', converted = base_convert(++index, characters.length);
        for (var i = 0; i < converted.length; i++) {
            number += characters.charAt(converted[i]);
        }
        $(this).attr('data-letter', number);
    });	
		});
});