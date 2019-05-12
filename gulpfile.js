const { src, dest, parallel, series, watch } = require('gulp');
const sass     = require('gulp-sass');
const sync     = require('browser-sync').create();
const gcmq     = require('gulp-group-css-media-queries');
const concat   = require('gulp-concat');
const notify   = require('gulp-notify');

let paths = {
	sync: 'board/admin',
	//sync: 'board',
	module: 'backend',
	//module: 'frontend',
}

function browserSync(done) {
	sync.init({
		proxy: paths.sync
	});
	done();
}

function browserSyncReload(done) {
	sync.reload();
	done();
}

function sassFile() {
	return src(paths.module + '/web/sass/*.+(sass|scss)')
		.pipe(sass().on('error', notify.onError(function (error) {
			return 'error-sass: ' + error.message;
		})))
		.pipe(gcmq())
		.pipe(dest(paths.module + '/web/css'))
		.pipe(sync.stream());
}

function watchFile() {
	watch(paths.module + '/web/sass', sassFile);
	watch(paths.module + '/**/*.php', browserSyncReload);
	watch(paths.module + '/web/js/*.js', browserSyncReload);
	watch('common/**/*.php', browserSyncReload);
}

exports.watch = parallel(watchFile, browserSync);