# wordpress-gen2-plugin

![example workflow](https://github.com/WIPACrepo/wordpress-gen2-plugin/actions/workflows/ci.yml/badge.svg)
![GitHub release (latest by date including pre-releases)](https://img.shields.io/github/v/release/WIPACrepo/wordpress-gen2-plugin?include_prereleases)

Custom features for the IceCube-Gen2 WordPress website

## Deployment

Download the zip file from a release and upload to the WordPress plugins page.

## Testing

You can run the build process inside a node.js container if you do not have
it installed locally:

```
docker run -it -v $PWD:/data -w /data node:16 npm install
docker run -it -v $PWD:/data -w /data node:16 npm run lint:css
docker run -it -v $PWD:/data -w /data node:16 npm run lint:js
docker run -it -v $PWD:/data -w /data node:16 npm run build
docker run -it -v $PWD:/data -w /data node:16 npm plugin-zip
```

