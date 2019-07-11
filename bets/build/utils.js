'use strict'
const path = require('path')
const fs = require('fs')
const config = require('../config')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const packageConfig = require('../package.json')
const HtmlWebpackPlugin = require('html-webpack-plugin')


function iterateFolder (foldername) {
  const path = require('path')
  const fs = require('fs')
  let files = [];

  for (let child of fs.readdirSync(__dirname, foldername))
  {
    console.log(child);
    if (fs.statSync(path.join(__dirname, child)).isDirectory())
    {
      files.push(child);
      // contents[path] = require(path.join(__dirname, child, 'content.yml'))
    }
  }

  return files;
}



// const getHtmlChunks = function(dirpath, callback, regexp=/.*/ig) {
//   const  basePath = resolve(dirpath);
  
  
//   function makeChunks (dirname) {
//     fs.readdir(dirname, function(readDirErr, files) {
//       console.log('third');

//       if (readDirErr) {
//         console.error(readDirErr);
//       } else {
//         let chunks = [];

//         files.forEach(entity => {
//           const pathToEntity = path.join(dirname, entity);

//           fs.stat(pathToEntity, function(statsErr, stats) {
//             console.log('fourth');
//             if (statsErr) {
//               console.error(statsErr);
//             } else {

//               if (stats.isDirectory()) {
//                 makeChunks(pathToEntity);
//               } else {

//                 let isCreateChunkAllowed = false;

//                 if (regexp.test(entity)) {
//                   isCreateChunkAllowed = true;
//                 }

                
                
//                 isCreateChunkAllowed && callback(null, {
//                     path: entity,
//                     subdir: dirname
//                 });
                
//               }
//             } 
//           }); // end fs.stat
//         }); // end files.forEach
        
//       } // end else
//     }); // end fs.readdir
//   }  // end readDir

//   makeChunks(basePath);
//   console.log('first');
// }
function resolve (dir) {
  return path.join(__dirname, '..', dir)
}

// let htmlTemplatesChunks = [];

// exports.extractHtmlChunks = function() {
//   let chunks = [];

//   getHtmlChunks('templates', function(err, data) {
//     chunks.push(data);
//     console.log(chunks);
//   });
// } 
exports.generateHtmlPlugins = function(templateDir) {
  const templateFiles = fs.readdirSync(resolve(templateDir));

  return templateFiles.map(item => {
    // Split names and extension
    const parts = item.split('.');
    const name = parts[0];
    const extension = parts[1];

    const htmlOptions = {
      filename: `${name}.html`,
      template: `${templateDir}/${name}.${extension}`,
      // minify: true
    };
    console.log(htmlOptions);

    return new HtmlWebpackPlugin(htmlOptions);
  });
};



exports.assetsPath = function (_path) {
  const assetsSubDirectory = process.env.NODE_ENV === 'production'
    ? config.build.assetsSubDirectory
    : config.dev.assetsSubDirectory

  return path.posix.join(assetsSubDirectory, _path)
}

exports.cssLoaders = function (options) {
  options = options || {}

  const cssLoader = {
    loader: 'css-loader',
    options: {
      sourceMap: options.sourceMap
    }
  }

  const postcssLoader = {
    loader: 'postcss-loader',
    options: {
      sourceMap: options.sourceMap
    }
  }

  // generate loader string to be used with extract text plugin
  function generateLoaders (loader, loaderOptions) {
    const loaders = options.usePostCSS ? [cssLoader, postcssLoader] : [cssLoader]

    if (loader) {
      loaders.push({
        loader: loader + '-loader',
        options: Object.assign({}, loaderOptions, {
          sourceMap: options.sourceMap
        })
      })
    }

    // Extract CSS when that option is specified
    // (which is the case during production build)
    if (options.extract) {
      return ExtractTextPlugin.extract({
        use: loaders,
        fallback: 'vue-style-loader'
      })
    } else {
      return ['vue-style-loader'].concat(loaders)
    }
  }

  // https://vue-loader.vuejs.org/en/configurations/extract-css.html
  return {
    css: generateLoaders(),
    postcss: generateLoaders(),
    less: generateLoaders('less'),
    sass: generateLoaders('sass', { indentedSyntax: true }),
    scss: generateLoaders('sass'),
    stylus: generateLoaders('stylus'),
    styl: generateLoaders('stylus')
  }
}

// Generate loaders for standalone style files (outside of .vue)
exports.styleLoaders = function (options) {
  const output = []
  const loaders = exports.cssLoaders(options)

  for (const extension in loaders) {
    const loader = loaders[extension]
    output.push({
      test: new RegExp('\\.' + extension + '$'),
      use: loader
    })
  }

  return output
}

exports.createNotifierCallback = () => {
  const notifier = require('node-notifier')

  return (severity, errors) => {
    if (severity !== 'error') return

    const error = errors[0]
    const filename = error.file && error.file.split('!').pop()

    notifier.notify({
      title: packageConfig.name,
      message: severity + ': ' + error.name,
      subtitle: filename || '',
      icon: path.join(__dirname, 'logo.png')
    })
  }
}
