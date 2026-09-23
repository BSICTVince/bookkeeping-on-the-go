/**
 * Scroll-scrubbed Lottie section. Plain element in normal document flow —
 * NOT scroll-jacked, NOT pinned, no position:sticky. The animation frame is
 * tied directly to how far the element has scrolled through the viewport,
 * like scrubbing a video timeline. Adapted from a React/lottie-web spec to
 * this theme's vanilla-JS stack; the scrub math and driver loop match that
 * spec exactly.
 *
 * - raw progress: 0 when the element's top edge is about to enter from the
 *   bottom of the viewport, 1 once it has fully exited the top.
 * - hold-then-scrub: frame stays at 0 until raw progress reaches ~33%, then
 *   scrubs linearly from 33%->100% of scroll to 0%->100% of the animation.
 * - eased, not snapped: lerps toward the mapped target every tick instead
 *   of jumping straight to it.
 * - driven by a continuous requestAnimationFrame loop, not scroll events —
 *   this keeps it in sync through momentum scrolling on mobile, where
 *   scroll events can stop firing before the page actually settles.
 */
(function () {
	if (typeof lottie === 'undefined') { return; }

	var HOLD_UNTIL = 0.33;
	var LERP_FACTOR = 0.12;

	document.querySelectorAll('[data-lottie-scrub]').forEach(function (el) {
		var src = el.getAttribute('data-lottie-scrub');
		if (!src) { return; }

		var anim = lottie.loadAnimation({
			container: el,
			renderer: 'svg',
			loop: false,
			autoplay: false,
			path: src,
			rendererSettings: { preserveAspectRatio: 'xMidYMid meet' }
		});

		var smoothed = 0;
		var rafId = null;

		function rawProgress() {
			var rect = el.getBoundingClientRect();
			var vh = window.innerHeight;
			var denom = vh + rect.height;
			if (denom <= 0) { return 0; }
			return Math.min(1, Math.max(0, (vh - rect.top) / denom));
		}

		function mapProgress(raw) {
			if (raw <= HOLD_UNTIL) { return 0; }
			return (raw - HOLD_UNTIL) / (1 - HOLD_UNTIL);
		}

		function tick() {
			var target = mapProgress(rawProgress());
			smoothed += (target - smoothed) * LERP_FACTOR;
			if (Math.abs(target - smoothed) < 0.0005) { smoothed = target; }
			if (anim.totalFrames > 1) {
				anim.goToAndStop(smoothed * (anim.totalFrames - 1), true);
			}
			rafId = requestAnimationFrame(tick);
		}

		anim.addEventListener('DOMLoaded', function () {
			rafId = requestAnimationFrame(tick);
		});

		window.addEventListener('pagehide', function () {
			if (rafId) { cancelAnimationFrame(rafId); }
			anim.destroy();
		});
	});
})();
