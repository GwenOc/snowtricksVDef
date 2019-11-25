jQuery(document).ready(function ($) {

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }
  });
  $('.back-to-top').click(function () {
    $('html, body').animate({
      scrollTop: 0
    }, 1500, 'easeInOutExpo');
    return false;
  });

  //smooth scroll trick
  $('a[href^="#"]').on('click', function (e) {
      e.preventDefault();
   
      var targetEle = this.hash;
      var $targetEle = $(targetEle);
   
      $('html, body').stop().animate({
          'scrollTop': $targetEle.offset().top
      }, 1500, 'swing', function () {
          window.location.hash = targetEle;
      });
  });
});

    //SeeMedia
    $('#seeMedia').click(function () {
        $('.trickMedia').toggle('fast', function () {
            if (this.style.display === 'block') {
                $('#seeMedia').html('cacher les médias');
            } else {
                $('#seeMedia').html('voir les médias');
            }
        });
    });

    //MultiUpload Pictures and Videos
    jQuery('.add-another-collection-widget').click(function (e) {
        var list = jQuery(jQuery(this).attr('data-list'));
        // Try to find the counter of the list or use the length of the list
        var counter = list.data('widget-counter') | list.children().length;
        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data('widget-counter', counter);
        // create a new list element and add it to the list
        var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    //SeeMore
    var offset = 10;
    let userRating = document.querySelector('#seeMore');
    let pathloadmore = userRating.dataset.loadmore;
    let totalitem = userRating.dataset.totalitem;
    let id = userRating.dataset.id;
    let load_img = '<button class="btn btn-primary" type="button" id="load" disabled>\n' +
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>\n' +
        'Chargement...\n' +
        '</button>';

    $('#load-more').click(function () {
        $.ajax({
            url: pathloadmore,
            type: "GET",
            data: {offset: offset, id: id},
            dataType: "json",
            beforeSend: function () {
                $("#load-more").hide();
                $("#end").append(load_img);
            }
        }).done(function (data) {

            let render = JSON.parse(data);
            $('#content').append(render.html);

            offset = offset + 4;

            if (offset >= totalitem) {
                $('#end').remove();
            } else {
                $('#load').remove();
                $("#load-more").show();
            }
        }).fail(function (err) {
            $('#load-more').before(err);
        }).always(function () {
            //do  something whether request is ok or fail
        });
});


