<script src="/js/jquery.magnific-popup.js"></script>
<link rel="stylesheet" href="/css/magnific-popup.css">
<style>
        .mfp-content{
            height: 100%;
            max-height: 100%;
        }
        #help-container{
            display:none;
        }
</style>

<div id="help-container">
    <div id="help1" class="gallery">
        <a href="/img/help/Double Sided Flyer.png">1</a>
        <a href="/img/help/Double Sided Flyer2.png">2</a>
    </div>
    <div id="help2" class="gallery">
        <a href="/img/help/Square 4-page brochure.png">1</a>
        <a href="/img/help/Square 4-page brochure2.png">2</a>
        <a href="/img/help/Square 4-page brochure3.png">3</a>
        <a href="/img/help/Square 4-page brochure4.png">4</a>
    </div>
    <div id="help3" class="gallery">
        <a href="/img/help/Horizontal 4-page brochure.png">1</a>
        <a href="/img/help/Horizontal 4-page brochure2.png">2</a>
        <a href="/img/help/Horizontal 4-page brochure3.png">3</a>
        <a href="/img/help/Horizontal 4-page brochure4.png">4</a>
    </div>
    <div id="help4" class="gallery">
        <a href="/img/help/Vertical 4-page brochure.png">1</a>
        <a href="/img/help/Vertical 4-page brochure2.png">2</a>
        <a href="/img/help/Vertical 4-page brochure3.png">3</a>
        <a href="/img/help/Vertical 4-page brochure4.png">4</a>
    </div>

    <script>

        $( document ).on( "click", ".help", function(e) {
                e.preventDefault();
                var id = $(this).attr('href');
                $(id + ` a:first`).trigger('click');
        });

        $('.gallery').each(function() { // the containers for all your galleries
            $(this).magnificPopup({
                delegate: 'a', // the selector for gallery item
                type: 'image',
                verticalFit: true,
                gallery: {
                  enabled:true,
                  verticalFit: true, 
                }
            });
        });
    </script>
</div>