document.addEventListener("DOMContentLoaded", () => {
  const containers = document.querySelectorAll(".carousel3");
  if (!containers.length) {
    console.warn("Swiper container not found");
    return;
  }

  containers.forEach((container) => {
    const autoplayEnabled = container.dataset.autoplay === "1";
    const autoplaySpeed = parseInt(container.dataset.autoplaySpeed, 10) || 3000;
    const animationSpeed = parseInt(container.dataset.animationSpeed, 10) || 1600;
    const slidesPerView = parseInt(container.dataset.slidesPerView, 10) || 1;

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

      // Скроллбар (если есть в разметке)
      /*scrollbar: {
        el: container.querySelector('.swiper-scrollbar'),
        draggable: true,
      },*/

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
      grabCursor: true, // курсор «рука»
      watchOverflow: true, // отключает, если мало слайдов
      autoHeight: false, // авто-высота по активному
      effect: "slide", // 'slide' | 'fade' | 'cube' | 'coverflow' | 'flip' | 'cards'
    });
  });
});
