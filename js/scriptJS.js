document.addEventListener('DOMContentLoaded', function () {
    var accordionBtn = document.querySelector('.accordion-btn');
    var accordionContent = document.querySelector('.accordion-content');

    accordionBtn.addEventListener('click', function () {
        if (accordionContent.style.display === 'block') {
            accordionContent.style.display = 'none';
        } else {
            accordionContent.style.display = 'block';
        }
    });
});

