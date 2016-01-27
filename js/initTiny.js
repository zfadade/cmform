
  tinymce.init({
      selector: ".frTextArea",
      language: "fr_FR",
      language_url: '/cmform/js/langs/fr_FR.js',
      content_css: '/cmform/style/myStyles.css',
      height: 150,
      plugins: [
'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
'save table contextmenu directionality emoticons template paste textcolor'
      ],
      toolbar: "undo redo removeformat | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote | link image"
  });

tinymce.init({
      selector: ".enTextArea",
      language: "en",
      height: 150,
      plugins: [
'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
'save table contextmenu directionality emoticons template paste textcolor'
      ],
      toolbar: "undo redo removeformat | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote | link image"
  });

tinymce.init({
        selector: '.editablediv',
        inline: true
  });