document.addEventListener("DOMContentLoaded", () => {
  const containers = document.querySelectorAll(".carousel3");
  if (!containers.length) {
    console.warn("Swiper container not found");
    return;
  }

  containers.forEach((container) => {
    const autoplayEnabled = container.dataset.autoplay === "1";
    const autoplaySpeed = parseInt(container.dataset.autoplaySpeed, 10) || 1000;
    const animationSpeed = parseInt(container.dataset.animationSpeed, 10) || 1000;
    const slidesPerView = parseInt(container.dataset.slidesPerView, 10) || 1;
    const effect = container.dataset.effect || 'slide';

    // Навигация
    const next = container.querySelector('.swiper-button-next');
    const prev = container.querySelector('.swiper-button-prev');

    let spacesBetween = parseInt(container.dataset.spacesBetween, 10);
    spacesBetween = isNaN(spacesBetween) ? 20 : spacesBetween; // default 20px if not set or invalid
  
    var swiper = new Swiper(container, {
      direction: "horizontal", // 'horizontal' | 'vertical'
      loop: true, // бесконечный цикл
      /*loop: false
      rewind: true*/
      speed: animationSpeed, // скорость анимации (ms)
      slidesPerView: slidesPerView, // сколько слайдов видно
      spaceBetween: spacesBetween, // отступ между слайдами (px)
      watchSlidesProgress: true, // для динамических буллетов

      // Поведение перелистывания
      centeredSlides: false, // центрировать активный
      slidesPerGroup: 1, // листать по N слайдов
      initialSlide: 0, // стартовый индекс

      // Автопрокрутка
      autoplay: autoplayEnabled
        ? {
            delay: autoplaySpeed, // задержка (ms)
            disableOnInteraction: false, // не отключать после взаимодействия
            pauseOnMouseEnter: false, // не останавливать при наведении
          }
        : false,

      // Пагинация
      pagination: {
        el: container.querySelector('.swiper-pagination'),
        clickable: true,
        dynamicBullets: true,
      },

      // Навигация (стрелки)
      navigation: {
        nextEl: next,
        prevEl: prev,
      },

      // Управление с клавиатуры
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },

      // Управление колесом мыши
      mousewheel: {
        forceToAxis: true,
      },

      // Дополнительно часто используемые:
      grabCursor: false, // курсор «рука»
      watchOverflow: true, // отключает, если мало слайдов
      autoHeight: false, // авто-высота по активному
      effect: effect, // 'slide' | 'fade' | 'cube' | 'coverflow' | 'flip' | 'cards'


      // Добавление классов анимации при смене слайда
      on: {
        init: function () {
          // Запускаем анимацию для первого слайда при загрузке
          const self = this;
          // Используем setTimeout, чтобы Swiper успел отрендерить классы
          setTimeout(function() {
            runAnimation(self);
          }, 50);
        },
        activeIndexChange: function () {
          // Очищаем анимацию только у неактивных слайдов
          const items = this.el.querySelectorAll('.swiper-slide:not(.swiper-slide-active) .ani-item');
          items.forEach(el => {
            const ani = el.getAttribute('data-ani');
            el.classList.remove('animate__animated', ani);
          });
          
          // Запускаем анимацию на новом активном слайде с задержкой
          const self = this;
          setTimeout(function() {
            runAnimation(self);
          }, 50);
        },
      },
    });
  });
});

function runAnimation(slider) {
  // Ищем элементы только в активном слайде
  const activeSlide = slider.el.querySelector('.swiper-slide-active');
  const items = activeSlide.querySelectorAll('.ani-item');
  
  items.forEach(el => {
    const ani = el.getAttribute('data-ani'); // Берем название анимации из атрибута
    el.classList.add('animate__animated', ani);
  });
}
