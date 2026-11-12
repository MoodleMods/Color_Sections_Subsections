# Finish the submission build

## Current status

The code, automated tests and release workflow are prepared. They are **not a
successful Moodle CI run**. Do not submit the source bundle as the final tested
release. This environment cannot download PHP, Moodle or npm dependencies and
cannot start Chromium; the environment rejected network requests and browser sockets.

## Run the prepared checks

1. Extract the source bundle. Upload the **contents** of `sectioncolors/` into
   your GitHub repository root. Include `.github/workflows/ci.yml` (a hidden folder).
   Do not put `sectioncolors/` inside the repository as an extra directory.
2. Open GitHub **Actions > Validate and build Section Colors**. A push starts it
   automatically; **Run workflow** can start it manually.
3. Resolve any failed job before submission. The workflow deliberately does not
   hide PHP, documentation, build or test failures. Attach a failing job log for
   diagnosis; do not remove checks to make a red run appear green.
4. Once every matrix job passes, download the **submission-zip** artifact.
   GitHub wraps artifacts in a download ZIP: extract it and use the inner
   `local_sectioncolors-1.0.16.zip` as the Moodle install/Marketplace file.
5. Commit the generated `amd/build/*.min.js` and matching `.map` files from that
   inner ZIP back to the repository. Run CI again and tag the checked commit
   `v1.0.16`. Ensure the repository tag and submitted ZIP correspond.

The `candidate-*` artifacts are intermediate per-environment outputs. Use the final
`submission-zip` artifact, which is gated on success of the complete matrix.
The workflow uploads an artifact only; it does not publish a Marketplace listing
or a GitHub release and does not write to the repository.

## What the workflow covers

- Clean Moodle/plugin setup on 4.5 and 5.2 with PHP 8.3.
- PostgreSQL and MariaDB test jobs.
- PHP syntax, Moodle code/PHPMDoc checks, metadata validation and savepoints.
- Moodle Grunt JavaScript/CSS/Gherkin checks and actual generated AMD builds.
- PHPUnit tests for palette, privacy, invalid colors, navigation authorization,
  native subsection placement, course deletion and section deletion cleanup.
- Behat teacher selection/reset and student navigation scenarios.
- ZIP structure with directory entries, source-map presence and integrity.

A passing run does not substitute for testing an upgrade from an existing plugin
version, uninstall behavior, manual screen-reader checks, or verifying every
Marketplace listing requirement.

## Final site checks

Use a disposable copy of your Moodle installation, with developer debugging on:

- Install fresh; upgrade an existing installation without losing colors.
- Check teacher/admin changes and student views, including a direct settings-page
  attempt as a student; that attempt must be denied.
- Confirm a missing or invalid session key cannot save settings.
- Test subsections, hidden/dimmed headings, black/dark grey, long names and zoom.
- Use only the keyboard to open, select, reset and close choices; test a screen reader.
- Delete a section and course; confirm cleanup. Uninstall and confirm table removal.
- For large courses, check loading/saving time and input-variable limits.
- Record exact Moodle version, PHP version, database and theme with the results.

## Marketplace listing

Use the actual public repository URL, its enabled issue tracker URL, documentation
URL, branch and version tag. Include screenshots from your own tested Moodle site.
Keep student-identifying data out of screenshots. Select only versions supported
by actual test evidence. Declare the backup/restore and custom-theme limitations.
Current Marketplace instructions remain authoritative; approval belongs to Moodle.

## Local validation in this environment

Passed: Node syntax checks; mocked picker behavior; XML parsing; YAML parsing;
fixed-palette preservation; source bundle integrity; release-packaging refusal
when compiled source maps are absent.

Not run successfully: PHP lint, Moodle PHPCS/PHPDoc, Grunt, PHPUnit, Behat,
full browser layout, live install/upgrade/uninstall or the CI workflow itself.

## References

- https://moodlehq.github.io/moodle-plugin-ci/
- https://moodlehq.github.io/moodle-plugin-ci/CLI.html
- https://moodledev.io/general/development/tools/nodejs
- https://moodledev.io/general/community/plugincontribution/checklist
  (marked legacy; follow its link to the current Marketplace submission guidance)
