<div id="my-modal-<?=get_the_ID()?>"><div class="modal-content"><span class="modal-close">&times;</span><?=the_content();?></div></div>
    <script>

jQuery(document).ready(function () {

    // Get the modal
    var modal_<?=get_the_ID()?> = document.querySelector('#my-modal-<?=get_the_ID()?>');

    // Get the button that opens the modal
    var btn = document.querySelectorAll('<?=$trigger[0]?>');

    // Get the <span> element that closes the modal
    var span = document.querySelector(".modal-close");

    // When the user clicks on the button, open the modal
   jQuery(btn).each(function (index, element) {
        element.onclick = function() {
            modal_<?=get_the_ID()?>.style.display = "block";
        }
    });

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
    modal_<?=get_the_ID()?>.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal_<?=get_the_ID()?>) {
            modal_<?=get_the_ID()?>.style.display = "none";
        }
    }
});
</script>
<style>
<?=$css[0]?>
</style>