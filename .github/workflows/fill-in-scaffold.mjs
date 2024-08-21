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
			'A8CSP Project Scaffold': repository.custom_properties['human-title'] ?? repository.name,
			'The scaffold for new projects used by the Automattic Special Projects team.': repository.description ?? '',
			'https://a8csp-project-scaffold-production.mystagingwebsite.com': repository.homepage ?? 'https://wpspecialprojects.com',
			'a8csp-project-scaffold': repository.name,
			'a8csp_project_scaffold': repository.custom_properties['php-globals-long-prefix'],
			'a8csp': repository.custom_properties['php-globals-short-prefix'],
			'A8CSP': repository.custom_properties['php-globals-short-prefix'].toUpperCase(),
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
