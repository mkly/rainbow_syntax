var RainbowSyntax = {
  init: function() {
    var t = this;
    t.$el = $('#' + t.identifier);
    if (!CCM_EDIT_MODE) { 
      Rainbow.color(
        t.data.code,
        t.data.language,
        function(syntastified) {
          t.$el.html(syntastified)
               .fadeIn('slow');
        }
      );
    } else {
      t.$el.text(t.data.code)
           .show();
    }
  }
}
