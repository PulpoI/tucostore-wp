const gulp = require("gulp");
const imagemin = require("gulp-imagemin");
const webp = require("gulp-webp");

// Set paths for dest and source files
var paths = {
  images: {
    src: [
      "./assets/img/**/*.jpg",
      "./assets/fav/**/*.jfif",
      "./assets/img/**/*.jpeg",
      "./assets/img/**/*.png",
      "./assets/img/**/*.svg",
      "./assets/img/**/*.gif",
      "./assets/img/**/*.webp",
      "./assets/fav/**/*.mp4",
    ],
    dest: "./dist/img",
  },
};

// function images_compression(done) {
//   return gulp
//     .src(paths.images.src)
//     .pipe(
//       imagemin([
//         imagemin.mozjpeg({
//           quality: 60,
//           progressive: true,
//         }),
//       ])
//     )
//     .pipe(gulp.dest(paths.images.dest));
// }

function convertWebp(done) {
  return gulp
    .src(paths.images.src)
    .pipe(webp({ quality: 40 }))
    .pipe(gulp.dest(paths.images.dest));
}

// Compress images
// gulp.task("compress-images", gulp.series(images_compression));
gulp.task("compress-webp", gulp.series(convertWebp));

// Build for prod
gulp.task("build", gulp.series(convertWebp));
