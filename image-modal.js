var imgs = document.getElementsByTagName('img');
//read all images and add an on click event
for (var i = 0; i < imgs.length; i++) {
    imgs[i].addEventListener('click', function(event) {
        event.preventDefault();
        var imgUrl = this.getAttribute('data-large_image');
        var modal = createModal(imgUrl);
        document.body.appendChild(modal);
    });
}

//when an image is clicked, create a new modal and display it
function createModal(imgUrl) {
    var modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = '<div class="modal-content"><div class="modal_close_button_container"><a href="#" class="modal-close">X</a></div><img src="' + imgUrl + '"></div><div class="modal-background"></div>';
    modal.querySelector('.modal-background').addEventListener('click', function() {
        modal.parentNode.removeChild(modal);
    });
    modal.querySelector('.modal-close').addEventListener('click', function(event) {
		event.preventDefault();
        modal.parentNode.removeChild(modal);
    });
    return modal;
}