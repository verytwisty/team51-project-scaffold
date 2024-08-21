import { statSync } from 'fs';
import { readdir, readFile } from 'fs/promises';
import { writeFile } from 'fs/promises';
import { join as joinPath } from 'path';
import process from 'process';

const repository = JSON.parse( process.argv[2] );
const skip_dirs = [ '.github', '.git' ];

/**
 * @param {string} dirPath
 * @param {(filePath: string) => Promise<void>} callback
 */
const traverseDirectory = async ( dirPath, callback ) => {
	if ( skip_dirs.includes( dirPath ) ) {
		console.log( 'Skipping %s', dirPath );
		return;
	}
	console.log( 'Traversing %s', dirPath );

	const files = await readdir( dirPath ); // Read the contents of the directory
	for ( const file of files ) {
		const filePath = joinPath( dirPath, file );

		if ( statSync( filePath ).isFile() ) {
			await callback( filePath );
		} else { // Recursively traverse directories
			await traverseDirectory( filePath, callback );
		}
	}
};

/**
 * Build a template using envs
 * @param {string} filePath
 */
const buildTemplate = async ( filePath ) => {
	console.log( 'Building %s', filePath );

	const templateFile   = await readFile( filePath, 'utf-8' );
	let renderedTemplate = templateFile, replacements;

	if ( 'README.md' === filePath ) {
		replacements = {
			'EXAMPLE_REPO_NAME': repository.custom_properties['human-title'] ?? repository.name,
			'EXAMPLE_REPO_PROD_URL': repository.homepage ?? 'https://wpspecialprojects.com',
		};
	} else {
		replacements = {
			'Build Processes Demo': repository.custom_properties['human-title'] ?? repository.name,
			'A demo project for showcasing standardized build processes for various asset types.': repository.description ?? '',
			'https://a8csp-project-scaffold-production.mystagingwebsite.com': repository.homepage ?? 'https://wpspecialprojects.com',
			'build-processes-demo': repository.name,
			'build_processes_demo': repository.custom_properties['php-globals-long-prefix'],
			'bpd': repository.custom_properties['php-globals-short-prefix'],
			'BPD': repository.custom_properties['php-globals-short-prefix'].toUpperCase(),
		};
	}

	for ( const [ key, value ] of Object.entries( replacements ) ) {
		renderedTemplate = renderedTemplate.replaceAll( key, value );
	}

	if ( renderedTemplate !== templateFile ) {
		console.log( 'Changes were made. Overwriting file.' );
		await writeFile( filePath, renderedTemplate );
	}
};

await traverseDirectory( '.', buildTemplate );
