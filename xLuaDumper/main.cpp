#include <windows.h>

#include <fstream>
#include <iostream>

#define LUA_FUNC(RET, NAME, ...) \
	typedef RET (*_##NAME##F)(__VA_ARGS__); \
	_##NAME##F NAME; \
	loadFunc(NAME, #NAME);

typedef int lua_State; // Fake type
typedef int (*lua_Writer) (lua_State* L, const void* p, size_t sz, void* ud);

HINSTANCE dll;

template<typename T> void loadFunc(T& func, const char* name)
{
	func = (T)GetProcAddress(dll, name);
}

int writer(lua_State* L, const void* p, size_t sz, void* ud)
{
	((std::ofstream*)ud)->write((const char*)p, sz);
	return 0;
}

int main(int argc, char* argv[])
{
	if (argc < 2)
	{
		std::cout << "Usage: xLuaDumper.exe <Dll> [Lua=opcode.lua] [Out=out.luac]" << std::endl;
		return EXIT_SUCCESS;
	}

	dll = LoadLibraryA(argv[1]);
	if (!dll)
	{
		std::cerr << "Failed to load DLL" << std::endl;
		return EXIT_FAILURE;
	}

	LUA_FUNC(void, lua_close, lua_State * L);
	LUA_FUNC(void, lua_settop, lua_State * L, int index);
	LUA_FUNC(int, lua_dump, lua_State * L, lua_Writer writer, void* data, int strip);

	LUA_FUNC(lua_State*, luaL_newstate, void);
	LUA_FUNC(int, luaL_loadfilex, lua_State * L, const char* file, const char* mode);

	auto L = luaL_newstate();

	auto file = argc >= 3 ? argv[2] : "opcode.lua";
	if (luaL_loadfilex(L, file, nullptr)) {
		std::cerr << "Failed to load lua" << std::endl;
		return EXIT_FAILURE;
	}

	auto out = argc >= 4 ? argv[3] : "out.luac";
	std::ofstream of(out, std::ios::binary | std::ios::binary);
	if (lua_dump(L, writer, &of, true)) {
		std::cerr << "Failed to dump lua" << std::endl;
		return EXIT_FAILURE;
	}
	of.close();

	lua_close(L);

	std::cout << "Success: " << file << " => " << out << " via " << argv[1] << std::endl;
	return EXIT_SUCCESS;
}
