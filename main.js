
const openModalButtons = document.querySelectorAll('.open-modal-btn');


const closeModalButtons = document.querySelectorAll('.close-btn');


openModalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const modalId = button.getAttribute('data-modal');
    document.getElementById(modalId).style.display = 'flex'; 
  });
});


closeModalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const modalId = button.getAttribute('data-modal');
    document.getElementById(modalId).style.display = 'none'; 
  });
});


window.onclick = function(event) {
  const modals = document.querySelectorAll('.modal');
  modals.forEach(modal => {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  });
};

document.addEventListener('DOMContentLoaded', () => {
  const navLinks = document.querySelectorAll('.nav-block ul li a');
  const sections = document.querySelectorAll('.crud-section');

  navLinks.forEach(link => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      
     
      sections.forEach(section => section.classList.add('hidden'));
      
    
      const sectionId = link.id.replace('nav-', '') + '-section';
      document.getElementById(sectionId).classList.remove('hidden');
    });
  });
});




 
const galleryItems = document.querySelectorAll('.gallery-item img');
const popup = document.createElement('div');
popup.classList.add('popup');
const popupImg = document.createElement('img');
popup.appendChild(popupImg);
document.body.appendChild(popup);


galleryItems.forEach(item => {
  item.addEventListener('click', (e) => {
    popupImg.src = e.target.src; 
    popup.style.display = 'flex'; 
  });
});


popup.addEventListener('click', (e) => {
  if (e.target !== popupImg) {
    popup.style.display = 'none'; 
  }
});



let currentSlide = 0;
const futureEventsContainer = document.getElementById('futureEventsContainer');
const futureEventCards = document.querySelectorAll('.future-event-card');
const totalSlides = futureEventCards.length;


document.getElementById('futureLeftArrow').addEventListener('click', () => {
    if (currentSlide > 0) {
        currentSlide--;
        updateSliderPosition();
    }
});

document.getElementById('futureRightArrow').addEventListener('click', () => {
    if (currentSlide < totalSlides - 1) {
        currentSlide++;
        updateSliderPosition();
    }
});


function updateSliderPosition() {
    const cardWidth = futureEventCards[0].clientWidth + 10; 
    const newTransformValue = -(currentSlide * cardWidth);
    futureEventsContainer.style.transform = `translateX(${newTransformValue}px)`;

    
    document.getElementById('futureLeftArrow').style.display = currentSlide === 0 ? 'none' : 'block';
    document.getElementById('futureRightArrow').style.display = currentSlide === totalSlides - 1 ? 'none' : 'block';
}


document.getElementById('futureLeftArrow').style.display = 'none'; 
updateSliderPosition();



