---
description: Write every project file as a tree to file-tree.md, with helpers.php functions listed by name.
---

Run from the project root. The only file you write is `file-tree.md`; do not touch source, tests, or rules.

## Build the list

Start from the tracked files plus the untracked ones that are not ignored:

```bash
git ls-files --cached --others --exclude-standard
```

If the directory is not a Git repository, fall back to `find . -type f`. Never include:

- `vendor/`, `node_modules/`, `storage/`, `bootstrap/cache/`, `public/build/`, `coverage/`, `.phpunit.cache/`
- `.git/`, `.idea/`, `.vscode/`, `.kilo/`, `.cursor/`
- `file-tree.md` itself
- lock files: `composer.lock`, `package-lock.json`, `pnpm-lock.yaml`, `yarn.lock`
- binary assets and build output: images, fonts, archives, media, compiled CSS/JS

## Write the tree

Overwrite `file-tree.md` with the heading and counts, then the tree **inside a fenced `text` block**:

````markdown
# File tree — <project>

<N> directories, <M> files

```text
<project>/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── UserController.php
│   └── helpers.php
├── composer.json
└── README.md
```
````

The fence is mandatory. Without it Markdown treats the tree as one paragraph and every directory and file runs together on a single line; with it, the preview keeps the tree.

- One entry per line inside the fence; directories end with `/`.
- Depth-first, directories before files, alphabetical at each level (case-insensitive).
- Omit a directory when it has no included files.
- No sizes, dates, or annotations — the tree is for reading the project, not auditing metadata.

## Helper functions

After the tree, list every function declared in each `helpers.php`, by name only:

```markdown
## Helper functions

### `src/helpers.php`

- `toList`
- `runInChunks`
```

Read the file, collect the `function <name>(` declarations in source order, and deduplicate. Do not include closures or class methods. If the project has no `helpers.php`, write `No helpers.php in this project.`

## Finish

Overwrite `file-tree.md` on every run and tell the developer to add `/file-tree.md` to `.gitignore`; never commit it.

Read the file back before reporting done and verify:

- it has exactly two fence lines (`grep -c '^```' file-tree.md` prints `2`);
- the tree sits between them, one entry per line;
- `## Helper functions` follows the closing fence.

If the fence is missing or the tree is on one line, rewrite the file.
