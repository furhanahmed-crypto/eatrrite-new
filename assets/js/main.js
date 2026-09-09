(function () {
    /* Mobile nav */
    var toggle = document.querySelector(".nav-toggle");
    var nav = document.querySelector(".site-nav");
    if (toggle && nav) {
        toggle.addEventListener("click", function () {
            nav.classList.toggle("is-open");
            toggle.classList.toggle("is-open");
        });
    }

    /* FAQ accordion with smooth height animation */
    var faqs = document.querySelectorAll(".faq-item");

    function setFaqHeight(item, open) {
        var answer = item.querySelector(".faq-answer");
        if (!answer) return;
        if (open) {
            answer.style.height = answer.scrollHeight + "px";
        } else {
            answer.style.height = "0px";
        }
    }

    faqs.forEach(function (item) {
        var button = item.querySelector(".faq-question");
        if (!button) return;

        /* Initialize open item height */
        if (item.classList.contains("is-open")) {
            setFaqHeight(item, true);
        }

        button.addEventListener("click", function () {
            var willOpen = !item.classList.contains("is-open");

            faqs.forEach(function (other) {
                if (other === item) return;
                other.classList.remove("is-open");
                setFaqHeight(other, false);
            });

            if (willOpen) {
                item.classList.add("is-open");
                setFaqHeight(item, true);
            } else {
                item.classList.remove("is-open");
                setFaqHeight(item, false);
            }
        });
    });

    window.addEventListener("resize", function () {
        faqs.forEach(function (item) {
            if (item.classList.contains("is-open")) setFaqHeight(item, true);
        });
    });

    /* Sticky header */
    var header = document.querySelector(".site-header");
    if (header) {
        var onScroll = function () {
            header.classList.toggle("is-sticky", window.scrollY > 20);
        };
        window.addEventListener("scroll", onScroll, { passive: true });
        onScroll();
    }

    /* Hero slider */
    var slides = document.querySelectorAll(".hero-slide");
    if (slides.length > 1) {
        var currentSlide = 0;
        setInterval(function () {
            slides[currentSlide].classList.remove("active");
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add("active");
        }, 5000);
    }

    /* Video modal */
    var videoModal = document.getElementById("videoModal");
    var videoFrame = document.getElementById("videoFrame");
    var videoError = document.getElementById("videoError");

    function openVideoModal() {
        if (!videoModal) return;
        videoModal.classList.add("is-open");
        videoModal.setAttribute("aria-hidden", "false");
        document.body.classList.add("modal-open");

        if (videoFrame) {
            var badSrc = videoFrame.getAttribute("data-src") || "";
            videoFrame.src = badSrc;
            /* Intentional invalid YouTube id — show error UI in place of video */
            if (videoError) {
                videoError.hidden = false;
            }
            videoFrame.addEventListener(
                "load",
                function () {
                    if (videoError) videoError.hidden = false;
                },
                { once: true }
            );
        }
    }

    function closeVideoModal() {
        if (!videoModal) return;
        videoModal.classList.remove("is-open");
        videoModal.setAttribute("aria-hidden", "true");
        document.body.classList.remove("modal-open");
        if (videoFrame) videoFrame.src = "";
        if (videoError) videoError.hidden = true;
    }

    document.querySelectorAll(".js-open-video").forEach(function (el) {
        el.addEventListener("click", function (e) {
            e.preventDefault();
            openVideoModal();
        });
    });
    var playWrap = document.querySelector(".hero-play-wrap");
    if (playWrap) {
        playWrap.addEventListener("click", function (e) {
            if (e.target.closest(".js-open-video")) return;
            e.preventDefault();
            openVideoModal();
        });
    }
    document.querySelectorAll(".js-close-video").forEach(function (el) {
        el.addEventListener("click", closeVideoModal);
    });
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeVideoModal();
    });

    /**
     * Split heading into words > letters.
     * Overflow is on each WORD (not the whole line) so multi-line titles are never clipped.
     */
    function splitHeading(el) {
        if (!el || el.dataset.split === "1") return;
        var text = el.textContent.trim();
        el.setAttribute("aria-label", text);
        el.innerHTML = "";

        var line = document.createElement("div");
        line.className = "split-line";

        text.split(/\s+/).forEach(function (word, index, words) {
            var wordEl = document.createElement("span");
            wordEl.className = "split-word";

            Array.from(word).forEach(function (char) {
                var charEl = document.createElement("span");
                charEl.className = "split-char";
                charEl.textContent = char;
                wordEl.appendChild(charEl);
            });

            line.appendChild(wordEl);
            /* Do not add an extra space node — spacing comes from CSS gap only */
        });

        el.appendChild(line);
        el.dataset.split = "1";
        el.classList.add("is-ready");
    }

    /* Animate only when enough of the element is actually on screen */
    var VIEW_THRESHOLD = 0.28; /* ~28% of the element must be visible */
    var VIEW_ROOT_MARGIN = "0px 0px -20% 0px"; /* ignore bottom 20% of viewport */

    function viewThresholds(minRatio) {
        var steps = [0, minRatio, 0.4, 0.55, 0.7, 1];
        return steps.filter(function (v, i, arr) {
            return arr.indexOf(v) === i;
        });
    }

    function revealOnEnter(elements, options) {
        if (typeof gsap === "undefined") return;

        var opts = options || {};
        var minRatio = opts.threshold != null ? opts.threshold : VIEW_THRESHOLD;
        var list = gsap.utils.toArray(elements).filter(function (el) {
            return el && el.dataset.revealed !== "1";
        });
        if (!list.length) return;

        if (typeof IntersectionObserver === "undefined") {
            list.forEach(function (el) {
                el.dataset.revealed = "1";
                gsap.set(el, { autoAlpha: 1, y: 0 });
            });
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    if (entry.intersectionRatio < minRatio) return;

                    var el = entry.target;
                    if (el.dataset.revealed === "1") return;
                    el.dataset.revealed = "1";
                    observer.unobserve(el);

                    gsap.to(el, {
                        autoAlpha: 1,
                        y: 0,
                        duration: opts.duration || 0.85,
                        ease: "power3.out",
                        clearProps: "transform"
                    });
                });
            },
            {
                threshold: viewThresholds(minRatio),
                rootMargin: opts.rootMargin || VIEW_ROOT_MARGIN
            }
        );

        list.forEach(function (el) {
            gsap.set(el, {
                autoAlpha: 0,
                y: opts.y != null ? opts.y : 48
            });
            observer.observe(el);
        });
    }

    function revealSplitTitle(heading, chars) {
        if (typeof IntersectionObserver === "undefined") {
            gsap.set(chars, { yPercent: 0, autoAlpha: 1 });
            return;
        }

        gsap.set(chars, { yPercent: 110, autoAlpha: 0 });

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    if (entry.intersectionRatio < VIEW_THRESHOLD) return;
                    if (heading.dataset.revealed === "1") return;
                    heading.dataset.revealed = "1";
                    observer.unobserve(heading);

                    gsap.to(chars, {
                        yPercent: 0,
                        autoAlpha: 1,
                        duration: 0.7,
                        ease: "power3.out",
                        stagger: 0.022,
                        clearProps: "transform"
                    });
                });
            },
            {
                threshold: viewThresholds(VIEW_THRESHOLD),
                rootMargin: VIEW_ROOT_MARGIN
            }
        );

        observer.observe(heading);
    }

    function initGsapAnimations() {
        if (typeof gsap === "undefined") {
            document.querySelectorAll(".split-title.is-ready .split-char").forEach(function (c) {
                c.style.opacity = "1";
                c.style.transform = "none";
            });
            return;
        }

        if (typeof ScrollTrigger !== "undefined") {
            gsap.registerPlugin(ScrollTrigger);
        }

        /* ---------- Letter-split headings ---------- */
        document.querySelectorAll(".split-title").forEach(function (heading) {
            splitHeading(heading);
            var chars = heading.querySelectorAll(".split-char");
            if (!chars.length) return;

            if (heading.classList.contains("hero-title")) {
                gsap.fromTo(
                    chars,
                    { yPercent: 110, autoAlpha: 0 },
                    {
                        yPercent: 0,
                        autoAlpha: 1,
                        duration: 0.7,
                        ease: "power3.out",
                        stagger: 0.022,
                        delay: 0.15,
                        clearProps: "transform"
                    }
                );
                return;
            }

            revealSplitTitle(heading, chars);
        });

        /* ---------- Hero content ---------- */
        var heroContent = document.querySelector(".hero-content");
        if (heroContent) {
            gsap.fromTo(
                heroContent.querySelectorAll(".eyebrow, .pill-label, .hero-text, .hero-actions"),
                { autoAlpha: 0, y: 28 },
                {
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.8,
                    ease: "power3.out",
                    stagger: 0.1,
                    delay: 0.35,
                    clearProps: "transform"
                }
            );
        }

        /* ---------- Labels / leads ---------- */
        document.querySelectorAll(".pill-label, .eyebrow, .section-lead, .blog-heading__lead").forEach(function (el) {
            if (el.closest(".hero-content") || el.closest(".hero-slider")) return;
            revealOnEnter(el, { y: 24, duration: 0.7, threshold: 0.35 });
        });

        /* ---------- Cards — each waits until enough of THAT card is visible ---------- */
        [
            ".svc-card",
            ".feature-card",
            ".process-card",
            ".program-card",
            ".faq-item",
            ".blog-card",
            ".testimonial-card",
            ".services-grid .card",
            ".pricing-card"
        ].forEach(function (selector) {
            revealOnEnter(document.querySelectorAll(selector), {
                y: 56,
                duration: 0.9,
                threshold: 0.3
            });
        });

        /* ---------- FAQ illustration ---------- */
        revealOnEnter(document.querySelectorAll(".faq-illustration"), {
            y: 40,
            duration: 0.95,
            threshold: 0.3
        });

        /* ---------- About / split media ---------- */
        revealOnEnter(document.querySelectorAll(".about-photos"), {
            y: 40,
            duration: 0.95,
            threshold: 0.28
        });
        document.querySelectorAll(".split > div:not(.about-photos)").forEach(function (copy) {
            revealOnEnter(copy.querySelectorAll("p:not(.eyebrow), .mini-list, .btn, .mini-item"), {
                y: 28,
                duration: 0.8,
                threshold: 0.35
            });
        });

        /* ---------- CTA ---------- */
        revealOnEnter(document.querySelectorAll(".cta-box"), {
            y: 48,
            duration: 0.95,
            threshold: 0.3
        });

        /* ---------- Forms / appointment ---------- */
        revealOnEnter(document.querySelectorAll(".contact-grid > *, .appointment-layout > *"), {
            y: 36,
            duration: 0.85,
            threshold: 0.3
        });

        /* ---------- Page banner ---------- */
        var banner = document.querySelector(".page-banner .container");
        if (banner) {
            gsap.fromTo(
                banner.children,
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    clearProps: "transform"
                }
            );
        }

        /* ---------- Footer ---------- */
        var footerGrid = document.querySelector(".footer-grid");
        if (footerGrid) {
            revealOnEnter(footerGrid.children, {
                y: 28,
                duration: 0.8,
                threshold: 0.25,
                rootMargin: "0px 0px -8% 0px"
            });
        }

        /* Failsafe: only force-show elements that are clearly already on screen */
        setTimeout(function () {
            document
                .querySelectorAll(
                    ".svc-card, .feature-card, .process-card, .program-card, .testimonial-card, .blog-card, .faq-item, .cta-box, .pricing-card, .split-char"
                )
                .forEach(function (el) {
                    if (el.dataset.revealed === "1") return;
                    var rect = el.getBoundingClientRect();
                    var vh = window.innerHeight || 1;
                    var visible = Math.min(rect.bottom, vh) - Math.max(rect.top, 0);
                    var ratio = visible / Math.max(rect.height, 1);
                    if (ratio < VIEW_THRESHOLD || rect.top > vh * 0.7) return;
                    el.dataset.revealed = "1";
                    gsap.set(el, { autoAlpha: 1, y: 0, yPercent: 0, clearProps: "transform" });
                });
        }, 4000);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initGsapAnimations);
    } else {
        initGsapAnimations();
    }
})();
