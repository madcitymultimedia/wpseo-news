1. Create Architecture Decision Records

        adr new Title of your decision

   This will create a new, numbered ADR file and open it in your
   editor of choice (as specified by the VISUAL or EDITOR environment
   variable).

   To create a new ADR that supersedes a previous one (ADR 9, for example),
   use the -s option.

        adr new -s 9 Title of decision superseding decision #9

   This will create a new ADR file that is flagged as superseding
   ADR 9, and changes the status of ADR 9 to indicate that it is
   superseded by the new ADR.  It then opens the new ADR in your
   editor of choice.
   

2. For further information, use the built-in help:

        adr help
