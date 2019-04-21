const { src, dest, parallel, series, watch } = require('gulp');
const sass     = require('gulp-sass');
const sync     = require('browser-sync').create();
const gcmq     = require('gulp-group-css-media-queries');
const concat   = require('gulp-concat');
const notify   = require('gulp-notify');

function browserSync(done) {
	sync.init({
		proxy: 'board/admin'
	});
	done();
}

function browserSyncReload(done) {
	sync.reload();
	done();
}

function sassFile() {
	return src('backend/web/sass/*.+(sass|scss)')
		.pipe(sass().on('error', notify.onError(function (error) {
			return 'error-sass: ' + error.message;
		})))
		.pipe(gcmq())
		.pipe(dest('backend/web/css'))
		.pipe(sync.stream());
}

function watchFile() {
	watch('backend/web/sass', sassFile);
	watch('backend/**/*.php', browserSyncReload);
	watch('backend/web/js/*.js', browserSyncReload);
}

exports.watch = parallel(watchFile, browserSync);