# xLuaDumper
Load xlua.dll and corresponding lua5X.dll to dump luac for a script.

Also comes with two scripts for generating opcode diff and batch decompiling.

You may also check [My Blog (Chinese)](https://blog.berd.moe/archives/xlua-reverse-note/) for more details.

# Build
Simply load the solution via VisualStudio 2019 or higher, and click build button :)

# Usage
```shell
xLuaDumper.exe <Dll> [Lua=opcode.lua] [Out=out.luac]

diff.php <xLua DLL> <Original Lua DLL>

batch.php <unluac> <Input> <Output>
```
