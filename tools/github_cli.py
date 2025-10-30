#!/usr/bin/env python3
"""Simple CLI helper for GitHub-style commands.

Currently supports "@github list repos" which walks the filesystem
looking for directories that contain a `.git` folder. Each matching
repository is printed relative to the chosen root (default: the
current working directory).
"""

from __future__ import annotations

import argparse
import os
import sys
from typing import Iterable, List


def discover_git_repositories(root: str) -> List[str]:
    """Return sorted relative paths to git repositories under ``root``.

    Parameters
    ----------
    root:
        Filesystem location to start the search from. The path is
        resolved to an absolute location before the scan to ensure
        consistent relative paths in the output.
    """

    root = os.path.abspath(root)
    repositories = set()

    for dirpath, dirnames, _filenames in os.walk(root):
        if ".git" in dirnames:
            repo_path = os.path.relpath(dirpath, root)
            repositories.add("." if repo_path == "." else repo_path)
            # Avoid recursing into nested repositories.
            dirnames[:] = [name for name in dirnames if name != ".git"]

    return sorted(repositories)


def handle_list_repos(root: str) -> int:
    repos = discover_git_repositories(root)

    if not repos:
        print("No git repositories found.")
        return 0

    for repo in repos:
        print(repo)
    return 0


def parse_command(tokens: Iterable[str]) -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Utility for responding to @github commands.",
        add_help=False,
        allow_abbrev=False,
    )
    parser.add_argument(
        "--root",
        default=".",
        help="Directory to scan for repositories (default: current directory).",
    )
    parsed, unknown = parser.parse_known_args(list(tokens))

    if unknown:
        raise SystemExit(
            "Unexpected arguments after '@github list repos': " + " ".join(unknown)
        )

    return parsed


def main(argv: List[str] | None = None) -> int:
    argv = sys.argv[1:] if argv is None else argv

    if not argv:
        print("Expected a command such as '@github list repos'.", file=sys.stderr)
        return 1

    # Allow passing the command as a single string argument.
    if len(argv) == 1:
        argv = argv[0].split()

    if argv[0] != "@github":
        print("Unrecognised command prefix. Expected '@github'.", file=sys.stderr)
        return 1

    if len(argv) >= 3 and argv[1] == "list" and argv[2] == "repos":
        args = parse_command(argv[3:])
        return handle_list_repos(args.root)

    print(
        "Unsupported @github command. Currently only 'list repos' is implemented.",
        file=sys.stderr,
    )
    return 1


if __name__ == "__main__":  # pragma: no cover
    sys.exit(main())
