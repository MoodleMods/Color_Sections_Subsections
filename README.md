# Section colors

Section colors (`local_sectioncolors`) helps teachers organize Moodle courses by
applying matching background colors to section, subsection and activity titles in
the course index and main course page. It requires no Moodle core modifications.

## Features

- 28 colors, including pastel, fluorescent, brown, grey and black shades.
- ROYGBIV order followed by brown, grey and black; lightest to darkest in each family.
- Default first, plus a sample beside every color name and selected value.
- A narrow, vertical, scrollable picker with keyboard-operable buttons.
- White text on black and dark grey backgrounds.
- Activity colors follow their containing section or subsection. Indentation,
  icons, dates and unused activity row space are not given the title background.
- Colors are shared by course viewers; only users with `moodle/course:update`
  can change them.

## Version and compatibility

Version 1.0.16 is a beta submission candidate. Minimum install requirement is
Moodle 4.5. This is a compatibility floor, not certification of every later release.
Standard Boost-compatible course markup and native subsections are assumed.
The maintainer reports successful use of an earlier version on their installation;
exact test environment details still need recording.

The included GitHub Actions workflow targets Moodle 4.5 and 5.2, PHP 8.3,
PostgreSQL and MariaDB. These are test targets until the workflow passes.
Do not claim Moodle 5.0 or 5.1 support based only on this matrix.

## Installation and upgrades

Use the built plugin ZIP produced by the workflow's `submission-zip` artifact.
Install it through Site administration > Plugins > Install plugins, then complete
the upgrade. When updating, do not uninstall first: that deletes saved settings.

Manual installation: place the `sectioncolors` directory in Moodle's `local`
directory, or `public/local` when using the public-web-root layout. Visit
Site administration > Notifications. Administrators installing the built ZIP
need not install development tools or run a JavaScript build.

## Use

Open a course as an editing teacher or administrator. Select **Section colors**
above the left course index, or from the course navigation. Choose colors and
select **Save changes**. Select **Default (no color)** to remove a custom color.
Subsections can have their own independent colors.

## Privacy, permissions and lifecycle

Only course ID, section ID and a color key are stored in the plugin table.
The Privacy API declares that the plugin stores no personal data. There are no
external service requests or analytics. The settings page checks course login,
editing capability and the session key. Submitted colors are limited to the palette.
Course and section deletion events remove corresponding color records. Moodle's
normal uninstall process removes the plugin's table.

## Known limitations

- Backup/restore, course copying and duplicated sections do not copy color settings.
- Custom themes, custom course formats, third-party nested subsections and the
  Moodle mobile app are not verified.
- The palette is designed for a light course background. Test custom/dark themes.
- Very large courses need performance and PHP input-limit testing.
- Full installation, upgrade, uninstall and accessibility testing is still required
  before representing this candidate as verified on a particular environment.

## Development and submission

See SUBMISSION.md for the build process, checks, known pending validation and
repository setup. This source bundle is not the final Marketplace ZIP. Existing
readable AMD runtime files are replaced by Moodle's generated minified files and
source maps during CI. The packaging helper refuses a release without source maps.

The repository must contain the plugin files directly at its root (for example,
`version.php`, `classes/`, and `.github/`), not inside an extra `sectioncolors/` folder.
A public issue tracker and real screenshots should accompany the listing. Repository
and issue-tracker URLs must refer to the actual published project, not placeholders.

## Changes in 1.0.16

- Add PHPUnit tests for palette/privacy, form validation, navigation permissions,
  native subsection ordering and real course/section deletion cleanup.
- Add Behat scenarios for teacher color selection/reset and student navigation.
- Add a GitHub Actions build/check matrix and a gated release artifact.
- Move picker layout to plugin-scoped CSS, add explicit group labels, and improve
  focus indication and long-label wrapping.
- Keep dimmed section links readable on dark chosen backgrounds.
- Preserve all saved palette keys, values and ordering; no database migration.

Version 1.0.15 added GPL documentation and audit cleanup. Version 1.0.14 halved the
picker width; preceding releases added the 28-color palette, samples and ordering.

## License

Copyright 2026 Rino Vincenti. GNU General Public License version 3 or later.
See LICENSE.txt. No third-party runtime libraries are bundled.
