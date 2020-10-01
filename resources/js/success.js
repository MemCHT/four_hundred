function success(message) {
  var html = '<p class="fade-item">'+ message +'</p>';
  $('body').append(html);

  setTimeout(function() {
    $('.fade-item').fadeOut();
  }, 1000);
}